<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los descuentos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision$
 * @modifiedby      $LastChangedBy$
 * @lastmodified    $Date$
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los descuentos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Descuento extends AppModel {

	var $order = array('Descuento.alta' => 'desc');
	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array(	'index'	=>
			array('contain'	=> array('Relacion' => array('Empleador', 'Trabajador'))),
								'edit'	=>
			array('contain'	=> array('Relacion'	=> array('Empleador', 'Trabajador'))),
								'add' 	=>
			array('valoresDefault'	=> array('alta'		=> array('date' => 'd/m/Y'),
											 'desde'	=> array('date' => 'd/m/Y'))));

	var $opciones = array('descontar'=> array(	'1'=>	'Con Cada Liquidacion',
												'2'=>	'Primera Quincena',
												'4'=>	'Segunda Quincena',
												'8'=>	'Sac',
												'16'=>	'Vacaciones',
												'32'=>	'Liquidacion Final',
											 	'64'=>	'Especial'));
							
	var $validate = array(
        'alta' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe ingresar una fecha valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe ingresar una fecha.'),
        ),
        'desde' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe ingresar una fecha valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe ingresar una fecha.'),
        )/*,
        'monto' => array(
			array(
				'rule'		=> VALID_NUMBER,
				'message'	=> 'Debe ingresar el monto a descontar.')
        )*/,
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el tipo de descuento.')
        ),
        'descripcion' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar la descripcion del descuento.')
        ),
        'relacion_id__' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la relacion laboral a la cual realizar el descuento.')
        )
	);


	var $breadCrumb = array('format' => '%s %s (%s)', 
							'fields' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));


	var $belongsTo = array('Liquidacion', 'Relacion');
	
	var $hasMany = array(	'Pago', 'DescuentosDetalle' =>
					array('className'    => 'DescuentosDetalle',
						  'foreignKey'   => 'descuento_id'));



/**
 * getDescuentos
 * TODO: Puede que haya dos conceptos de embargo concurrentes, entoces se pisarian.
 * Esto implica un gran cambio en el liquidador. Revisar.		   
 * Dada un ralacion XXXXXXXXXX.
 * @return array vacio si no hay nada que descontar.
 */
	function getDescuentos($relacion, $opciones) {
		
	   switch($opciones['tipo']) {
			case 'normal':
				if ($opciones['periodo']['periodo'] === '1Q') {
					$descontar = 3;
				} elseif ($opciones['periodo']['periodo'] === '2Q' || $opciones['periodo']['periodo'] === 'M') {
					$descontar = 5;
				}
				break;
			case 'sac':
				$descontar = 9;
			break;
			case 'vacaciones':
				$descontar = 17;
			break;
			case 'liquidacion_final':
				$descontar = 33;
			break;
			case 'especial':
				$descontar = 1;
			break;
		}
		
		$r = $this->find('all',
			array(
				  	'contain'		=> 'DescuentosDetalle',
	   				'order'			=> "
					order by	case Descuento.tipo
									when 'Cuota Alimentaria' then 0
									when 'Embargo' then 1
									when 'Vale' then 2
									when 'Prestamo' then 3
								end,
								Descuento.alta",
				  	'checkSecurity'	=> false,
					'conditions' 	=> array(
				array('OR'	=> array(	'Descuento.hasta' 		=> '0000-00-00',
										'Descuento.hasta >=' 	=> $opciones['periodo']['hasta'])),
				'Descuento.relacion_id' 						=> $relacion['Relacion']['id'],
				'Descuento.desde >=' 							=> $opciones['periodo']['desde'],

 				'(Descuento.descontar & ' . $descontar . ') >' 	=> 0,
 				'Descuento.estado' 								=> 'Activo')
		));

		$conceptos = $variables = $auxiliares = array();
        $index['Vale'] = 'a';
        $index['Prestamo'] = 'a';
        $index['Embargo'] = 'a';
        $index['Cuota Alimentaria'] = 'a';
		if (!empty($r)) {
            
            $Concepto = ClassRegistry::init('Concepto');
			foreach ($r as $k => $v) {

                $tipos[] = $v['Descuento']['tipo'];
                $name = Inflector::underscore(str_replace(' ', '', $v['Descuento']['tipo'] . '_' . $index[$v['Descuento']['tipo']]));
                $index[$v['Descuento']['tipo']]++;
                
                $conceptos[] = $Concepto->findConceptos('ConceptoPuntual', array_merge(
                        array('relacion' => $relacion, 'codigoConcepto' => $name), $opciones));
                
                $variables['total_descontado_' . $name] = 0;
                $variables['cuotas_descontadas_' . $name] = 0;
                $variables['monto_' . $name] = $v['Descuento']['monto'];
                $variables['cuotas_' . $name] = $v['Descuento']['cuotas'];
                if (!empty($v['DescuentosDetalle'])) {
                    $variables['total_descontado_' . $name] = array_sum(Set::extract('/DescuentosDetalle/monto', $v['DescuentosDetalle']));
                    $variables['cuotas_descontadas_' . $name] = count($v['DescuentosDetalle']);
                }


				/** Check for concurrency */
				if ($v['Descuento']['concurrencia'] === 'Solo uno a la vez') {
					if (empty($concurrencia[$v['Descuento']['tipo']])) {
						$concurrencia[$v['Descuento']['tipo']] = true;
					} else {
						continue;
					}
				}

				
				/** Creo un registro el la tabla auxiliar que debera ejecutarse en caso de que se confirme la pre-liquidacion. */
				$auxiliar = null;
				$auxiliar['descuento_id'] = $v['Descuento']['id'];
				$auxiliar['fecha'] = '##MACRO:fecha_liquidacion##';
				$auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
				$auxiliar['monto'] = '##MACRO:concepto_valor##';
				$auxiliares[] = array('save' => serialize($auxiliar), 'model' => 'DescuentosDetalle');
			}
		}
        return array(
                    'conceptos'    => $conceptos,
                    'variables'    => $variables,
                    'auxiliar'     => $auxiliares);
	}


/**
 * descontar field is bitwise, must sum values then.
 */
	function beforeSave($options = array()) {
		if (isset($this->data['Descuento']['descontar']) && is_array($this->data['Descuento']['descontar'])) {
			$this->data['Descuento']['descontar'] = array_sum($this->data['Descuento']['descontar']);
		}
		if ($this->data['Descuento']['tipo'] === 'Vale') {
			$this->data['Descuento']['cuotas'] = 1;
		}

		/** Must create a pending peyment */
		if (empty($this->data['Descuento']['id']) && in_array($this->data['Descuento']['tipo'], array('Vale', 'Prestamo'))) {
			$this->data['Pago'][0]['fecha'] = $this->data['Descuento']['alta'];
			$this->data['Pago'][0]['relacion_id'] = $this->data['Descuento']['alta'];
			$this->data['Pago'][0]['relacion_id'] = $this->data['Descuento']['relacion_id'];
			$this->data['Pago'][0]['monto'] = $this->data['Descuento']['monto'];
			$this->data['Pago'][0]['moneda'] = 'Pesos';
			$this->data['Pago'][0]['estado'] = 'Pendiente';
		}
		return parent::beforeSave($options);
	}

	
}
?>