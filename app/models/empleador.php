<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los empleadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.models
 * @since			Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los empleadores.
 *
 * @package		pragtico
 * @subpackage	app.models
 */
class Empleador extends AppModel {
	
	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array("edit" =>array("contain"=>array("Localidad", "Actividad")),
								"add" =>array(								
										"valoresDefault"=>array("alta"=>"date('d/m/Y')",
																"pais"=>"Argentina")));

	
	var $validate = array( 
        'nombre' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=>'Debe especificar el nombre del empleador.')
        ),
        'cuit' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=>'Debe especificar el cuit del empleador.'),
			array(
				'rule'	=> 'validCuitCuil',
				'message'	=>'El numero de Cuit ingresado no es valido.')
				
        ),
        'alta' => array(
			array(
				'rule'	=> VALID_DATE,
				'message'	=>'La fecha no es valida.'),
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=>'Debe ingresar una fecha o seleccionarla desde el calendario.')
				
        ),
        'email' => array(
			array(
				'rule'	=> VALID_MAIL,
				'message'	=>'El email no es valido.')
        ),
        'provincia_id' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=>'Debe seleccionar la provincia.')
        )
        
	);

	var $belongsTo = array(	'Localidad' =>
                        array('className'    => 'Localidad',
                              'foreignKey'   => 'localidad_id'),
							'Actividad' =>
                        array('className'    => 'Actividad',
                              'foreignKey'   => 'actividad_id'));
	
	var $hasMany = array(	'Area' =>
                        array('className'    => 'Area',
                              'foreignKey'   => 'empleador_id'),
							'Suss' =>
                        array('className'    => 'Suss',
                              'foreignKey'   => 'empleador_id'),                              
							'Recibo' =>
                        array('className'    => 'Recibo',
                              'foreignKey'   => 'empleador_id'),
							'Cuenta' =>
                        array('className'    => 'Cuenta',
                              'foreignKey'   => 'empleador_id')                              );

	var $hasAndBelongsToMany = array(	'Trabajador' =>
									array('with' => 'Relacion'),
										'Rubro' =>
									array('with' => 'EmpleadoresRubro'),
										'Concepto' =>
									array('with' => 'EmpleadoresConcepto'),
										'Coeficiente' =>
									array('with' => 'EmpleadoresCoeficiente'));



	function beforeSave() {
		/**
		* Si las foraneas opcionales no las saco del array, en caso de que esten vacias, el framework intentara
		* guardarlas con el valor vacio, y este fallara.
		*/
		if(empty($this->data['Empleador']['actividad_id'])) {
			unset($this->data['Empleador']['actividad_id']);
		}
		return parent::beforeSave();
	}			
}
?>
