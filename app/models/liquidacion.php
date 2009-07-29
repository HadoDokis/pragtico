<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las liquidaciones.
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
 * La clase encapsula la logica de acceso a datos asociada a las liquidaciones.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Liquidacion extends AppModel {

	/**
	* Seteo los tipos posibles de liquidaciones que podre realizar.
	*/
	var $opciones = array('tipo' => array(
						  		'normal'			=> 'Normal',
			   					'sac'				=> 'Sac',
		   						'vacaciones'		=> 'Vacaciones',
		   						'final'	            => 'Final',
		   						'especial'			=> 'Especial'));
	
	var $hasMany = array(	'LiquidacionesDetalle' =>
                        array('className'   => 'LiquidacionesDetalle',
                              'foreignKey' 	=> 'liquidacion_id',
                              'order'		=> 'LiquidacionesDetalle.concepto_orden',
                              'dependent'	=> true),
                            'LiquidacionesError' =>
                        array('className'   => 'LiquidacionesError',
                              'foreignKey' 	=> 'liquidacion_id',
                              'dependent'	=> true),
                            'LiquidacionesAuxiliar' =>
                        array('className'   => 'LiquidacionesAuxiliar',
                              'foreignKey' 	=> 'liquidacion_id',
                              'dependent'	=> true),
							'Pago' =>
                        array('className'   => 'Pago',
                              'foreignKey' 	=> 'liquidacion_id',
                              'dependent'	=> true));

	var $belongsTo = array(	'Trabajador' =>
                        array('className'    => 'Trabajador',
                              'foreignKey'   => 'trabajador_id'),
							'Relacion' =>
                        array('className'    => 'Relacion',
                              'foreignKey'   => 'relacion_id'),
							'Empleador' =>
                        array('className'    => 'Empleador',
                              'foreignKey'   => 'empleador_id'),
							'Factura' =>
                        array('className'    => 'Factura',
                              'foreignKey'   => 'factura_id'));
                              

    var $__recursivityCounter = 0;
/**
 * I must overwrite default cakePHP deleteAll method because it's not performant when there're many 
 * relations and many records.
 * I also add transaccional behavior and a better error check.
 * TODO:
 * 		when the relation has a dependant relation, this method will not delete that relation.
 */	
	function deleteAll($conditions, $cascade = true, $callbacks = false) {
		$ids = Set::extract(
			$this->find('all', array_merge(array(
							'fields' 	=> $this->alias . '.' . $this->primaryKey,
							'recursive' => 0), compact('conditions'))),
			'{n}.' . $this->alias . '.' . $this->primaryKey
		);
		
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		$c = 0;
		$db->begin($this);
		foreach ($this->hasMany as $assoc => $data) {
			$table = $db->name(Inflector::tableize($assoc));
			$conditions = array($data['foreignKey'] => $ids);
			$sql = sprintf('DELETE FROM %s %s', $table, $db->conditions($conditions));
			$this->query($sql);

			if (empty($this->dbError)) {
				$c++;
			}
		}
		
		if (count($this->hasMany) === $c) {
			$sql = sprintf('DELETE FROM %s %s', $db->name($this->useTable), $db->conditions(array($this->primaryKey => $ids)));
			$this->query($sql);
			//$this->__buscarError();
			if (empty($this->dbError)) {
				$db->commit($this);
				return true;
			}
			else {
				$db->rollback($this);
				return false;
			}
		}
		else {
			$db->rollback($this);
			return false;
		}
	}
	

/**
 * Generates a liquidation.
 *
 * @param array $relationship. The complete relationship array.
 * @param array $period. The period (extended array style).
 * @param string $type. The type of recipt you want to generate.
 *      - normal
 *      - sac
 *      - bla
 *      - bla
 * @param array $options.
 *      - period: 1=first_half, 2=second_half.
 *      - year: The year where to calcula SAC.
 *      - january to december: Sum of remuneratives total by month.
 * @return array. A receipt ready to be saved.
 * @access public
 */
    function getReceipt($relationship, $period, $type = 'normal', $options = array()) {

		$this->__conceptos = array();
		$this->__receiptError = array();
		$this->__saveAuxiliar = array();
		$this->__variables = null;
        $this->__currentConcept = null;

		/** Initial set of vars and concepts */
		$this->setVar($options['variables']);
		if (!empty($options['informaciones'][$relationship['ConveniosCategoria']['convenio_id']])) {
			$this->setVar($options['informaciones'][$relationship['ConveniosCategoria']['convenio_id']]);
		}

		$this->setVar('#tipo_liquidacion', $type);
		$this->setPeriod($period);
		$this->setRelationship($relationship);

        $this->resetRecursivity();
		
		if ($type === 'normal' || $type === 'especial') {

			$jornada = $this->getRelationship('ConveniosCategoria', 'jornada');
			if (($period['periodo'] !== 'M' && $jornada === 'Mensual') || ($period['periodo'] === 'M' && $jornada === 'Por Hora')) {
				return;
			}
			
			$opcionesFindConcepto = null;
			$this->setConcept(
				$this->Relacion->RelacionesConcepto->Concepto->findConceptos('Relacion',
					array(		'relacion' 	=> $relationship,
								'desde' 	=> $this->getVarValue('#fecha_desde_liquidacion'),
								'hasta' 	=> $this->getVarValue('#fecha_hasta_liquidacion'))));

            /** Always must be present basic salary
            if (empty($this->__conceptos['sueldo_basico']) || $type === 'normal') {
                $this->setConcept($this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual',
                        array(  'relacion'          => $this->getRelationship(),
                                'codigoConcepto'    => 'sueldo_basico')));
            }
            */

			/** Get novelties */
			$novedades = $this->Relacion->Novedad->getNovedades($this->getRelationship(), $this->getPeriod());
			foreach ($novedades['variables'] as $varName => $varValue) {
				$this->setVar($varName, $varValue);
			}
			$this->__setAuxiliar($novedades['auxiliar']);
			$this->setConcept($novedades['conceptos']);

			/** Get hours */
			$horas = $this->Relacion->Hora->getHoras($this->getRelationship(), $this->getPeriod());
			foreach ($horas['variables'] as $horaTipo => $horaValor) {
				$this->setVar($horaTipo, $horaValor);
			}
			$this->__setAuxiliar($horas['auxiliar']);
			$this->setConcept($horas['conceptos']);

			/** Get absences */
			$ausencias = $this->Relacion->Ausencia->getAusencias($this->getRelationship(), $this->getPeriod());
			foreach ($ausencias['variables'] as $ausenciaTipo => $ausenciaValor) {
				$this->setVar($ausenciaTipo, $ausenciaValor);
			}
			$this->__setAuxiliar($ausencias['auxiliar']);
			$this->setConcept($ausencias['conceptos']);

			/** Get discounts */
			$discounts = $this->Relacion->Descuento->getDescuentos($this->getRelationship(),
					array(	'periodo' 	=> $this->getPeriod(),
							'tipo'		=> $type));
            foreach ($discounts['variables'] as $varName => $varValue) {
                $this->setVar($varName, $varValue);
            }
			$this->__setAuxiliar($discounts['auxiliar']);
			$this->setConcept($discounts['conceptos']);
		
			
		} elseif ($type === 'vacaciones') {
			foreach ($this->Relacion->RelacionesConcepto->Concepto->findConceptos('Relacion',
					array(		'relacion' 	=> $relationship,
								'desde' 	=> $this->getVarValue('#fecha_desde_liquidacion'),
								'hasta' 	=> $this->getVarValue('#fecha_hasta_liquidacion'))) as $cCod => $concepto) {

				if ($concepto['tipo'] === 'Deduccion') {
					$this->setConcept(array($cCod => $concepto));
				}
			}
			
			$this->setConcept($this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual',
					array(	'relacion' 			=> $this->getRelationship(),
							'codigoConcepto'	=> 'vacaciones')));

            //$this->LiquidacionesDetalle->Behaviors->detach('Permisos');
            //$this->LiquidacionesDetalle->Behaviors->detach('Util');

            App::import('Vendor', 'dates', 'pragmatia');
            $data = $this->Relacion->Liquidacion->LiquidacionesDetalle->find('all', array(
                'contain'       => array('Liquidacion', 'Concepto'),
                'group'         => array('CONCAT(Liquidacion.ano, LPAD(Liquidacion.mes, 2, \'0\'), Liquidacion.periodo)'),
                'fields'        => array('CONCAT(Liquidacion.ano, LPAD(Liquidacion.mes, 2, \'0\'), Liquidacion.periodo) AS periodo', 'SUM(LiquidacionesDetalle.valor) as valor'),
                'conditions'    => array(
                    'Concepto.plus_vacacional'                  => 'Si',
                    'Liquidacion.estado'                        => 'Confirmada',
                    'Liquidacion.relacion_id'                   => $relationship['Relacion']['id'],
                    'LiquidacionesDetalle.concepto_imprimir !=' => 'No',
                    'CONCAT(Liquidacion.ano, LPAD(Liquidacion.mes, 2, \'0\'), Liquidacion.periodo)' => Dates::getPeriods(
                        Dates::dateAdd($period['desde'], -365), $period['desde'],
                            array('fromInclusive' => false, 'toInclusive' => false)))));

            $this->setVar('#suma_conceptos_plus_vacacional_12_meses', array_sum(Set::extract('/LiquidacionesDetalle/valor')));
            
            $total = 0;
            $data = Set::combine($data, '{n}.LiquidacionesDetalle.periodo', '{n}.LiquidacionesDetalle.valor');
            $periods = Dates::getPeriods(null, $period['desde'],
                            array('fromInclusive' => false, 'toInclusive' => false, 'month' => -6));
            foreach ($data as $period => $value) {
                if (in_array($period, $periods)) {
                    $total += $value;
                }
            }
            $this->setVar('#suma_conceptos_plus_vacacional_6_meses', $total);
		} elseif (in_array($type,  array('final', 'sac'))) {

            if ($type === 'final') {

                $auxiliar = null;
                $auxiliar['id'] = $relationship['Relacion']['id'];
                $auxiliar['estado'] = 'Historica';
                $this->__setAuxiliar(array('save' => serialize($auxiliar), 'model' => 'Relacion'));
                
                $to = $relationship['Relacion']['egreso'];
                $tmp = explode('-', $relationship['Relacion']['egreso']);
                $period['ano'] = $tmp[0];
                $period['mes'] = $tmp[1];
                if ($period['mes'] <= 6) {
                    $period['periodo'] = '1S';
                } else {
                    $period['periodo'] = '2S';
                }
            }
			unset($options['variables']);
			unset($options['informaciones']);

            $conditions['Liquidacion.relacion_id'] = $relationship['Relacion']['id'];
			$conditions['Liquidacion.tipo !='] = 'Sac';
            $conditions['Liquidacion.estado'] = 'Confirmada';
            $options['year'] = $conditions['Liquidacion.ano'] = $period['ano'];
            if ($period['periodo'] == '1S') {
				$options['period'] = 1;
                $conditions['Liquidacion.mes >='] = 1;
                $conditions['Liquidacion.mes <='] = 6;
                $from = $period['ano'] . '-01-01';
                if (empty($to)) {
                    $to = $period['ano'] . '-06-30';
                }
                $period['hasta'] = $period['ano'] . '-06-30';
            } elseif ($period['periodo'] == '2S') {
				$options['period'] = 2;
                $conditions['Liquidacion.mes >='] = 7;
                $conditions['Liquidacion.mes <='] = 12;
                $from = $period['ano'] . '-07-01';
                if (empty($to)) {
                    $to = $period['ano'] . '-12-31';
                }
                $period['hasta'] = $period['ano'] . '-12-31';
            } else {
                return array('error' => sprintf('Wrong period (%s). Only "1" for the first_half or "2" for the second_half allowed for type %s.', $options['period'], $type));
            }
            
            $fields = array('Liquidacion.mes', 'SUM(Liquidacion.remunerativo) AS total_remunerativo');
            $groupBy = array('Liquidacion.mes');
            $r = $this->find('all', array(
                    'recursive'     => -1,
                    'fields'        => $fields,
                    'conditions'    => $conditions,
                    'group'         => $groupBy));
            if (!empty($r)) {
                $this->setVar('#mayor_suma_mes_remunerativo_semestre', max(Set::combine($r, '{n}.Liquidacion.mes',
                          '{n}.Liquidacion.total_remunerativo')));
            } else {
                $this->setVar('#mayor_suma_mes_remunerativo_semestre', 0);
            }


            if ($type === 'final') {
                $this->setVar('#fecha_desde_liquidacion', $from);
                $this->setVar('#fecha_hasta_liquidacion', $period['hasta']);
                $this->setConcept($this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual',
                        array(  'relacion'          => $this->getRelationship(),
                                'codigoConcepto'    => 'vacaciones_no_gozadas')));
            }

            $ausencias = $this->Relacion->Ausencia->getAbsencesByType(array('Accidente', 'Maternidad'), $relationship['Relacion']['id'], $from, $to);
            $this->setVar('#total_dias_ausencias_accidente_semestre', $ausencias['Accidente']);
            $this->setVar('#total_dias_ausencias_maternidad_semestre', $ausencias['Maternidad']);
 
            foreach ($this->Relacion->RelacionesConcepto->Concepto->findConceptos('Relacion',
                    array(      'relacion'  => $relationship,
                                'desde'     => $this->getVarValue('#fecha_desde_liquidacion'),
                                'hasta'     => $this->getVarValue('#fecha_hasta_liquidacion'))) as $cCod => $concepto) {

                if ($concepto['tipo'] === 'Deduccion') {
                    $this->setConcept(array($cCod => $concepto));
                }
            }
            $this->setConcept($this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual',
                    array(  'relacion'          => $this->getRelationship(),
                            'codigoConcepto'    => 'sac')));
            $this->__conceptos['sac'] = array_merge($this->__conceptos['sac'], $this->__getConceptValue($this->__conceptos['sac']));

        } elseif ($type === 'especial') {
            return $this->getReceipt($relationship, $period, 'normal', $options);
        }

		/** Resolv */
		foreach ($this->__conceptos as $cCod => $concepto) {
			$this->__conceptos[$cCod] = array_merge($this->__conceptos[$cCod],
					$this->__getConceptValue($concepto));
		}
		return $this->__getSaveArray($type);
    }
    



	function __getSaveArray($type) {
		/**
		* Preparo el array para guardar la pre-liquidacion.
		* Lo guardo como una liquidacion con estado "Sin Confirmar".
		* Cuando se confirma, solo cambio el estado, sino, a la siguiente pasada del preliquidador, la elimino.
		*/
		$liquidacion = null;
		$liquidacion['fecha'] = date('Y-m-d');
        if ($type === 'final') {
            list($liquidacion['ano'], $liquidacion['mes'], ) = explode('-', $this->getRelationship('Relacion', 'egreso'));
            $liquidacion['periodo'] = 'F';
            /**When final receipt, must pay whether two next days */
            $liquidacion['pago'] = $this->dateAddWorkingDays($this->getRelationship('Relacion', 'egreso'), 2);
        } else {
            $liquidacion['ano'] = $this->getPeriod('ano');
            $liquidacion['mes'] = $this->getPeriod('mes');
            $liquidacion['periodo'] = $this->getPeriod('periodo');
            $liquidacion['pago'] = $this->dateAddWorkingDays($this->getPeriod('hasta'), $this->getRelationship('Empleador', 'pago'));
        }
		$liquidacion['tipo'] = $this->getVarValue('#tipo_liquidacion');
		$liquidacion['estado'] = 'Sin Confirmar';
		$liquidacion['relacion_id'] = $this->getRelationship('Relacion', 'id');
		$liquidacion['relacion_ingreso'] = $this->getRelationship('Relacion', 'ingreso');
        $liquidacion['relacion_egreso'] = $this->getRelationship('Relacion', 'egreso');
		$liquidacion['relacion_legajo'] = $this->getRelationship('Relacion', 'legajo');
		$liquidacion['relacion_horas'] = $this->getRelationship('Relacion', 'horas');
		$liquidacion['relacion_basico'] = $this->getRelationship('Relacion', 'basico');
		$liquidacion['relacion_area_id'] = $this->getRelationship('Relacion', 'area_id');
		$liquidacion['relacion_antiguedad'] = $this->getVarValue('#anos_antiguedad');
        $liquidacion['relacion_antiguedad_reconocida'] = $this->getRelationship('Relacion', 'antiguedad_reconocida');
		$liquidacion['trabajador_id'] = $this->getRelationship('Trabajador', 'id');
		$liquidacion['trabajador_cuil'] = $this->getRelationship('Trabajador', 'cuil');
		$liquidacion['trabajador_nombre'] = $this->getRelationship('Trabajador', 'nombre');
		$liquidacion['trabajador_apellido'] = $this->getRelationship('Trabajador', 'apellido');
		$liquidacion['trabajador_cbu'] = $this->getRelationship('Trabajador', 'cbu');
		$liquidacion['empleador_id'] = $this->getRelationship('Empleador', 'id');
		$liquidacion['empleador_cuit'] = $this->getRelationship('Empleador', 'cuit');
		$liquidacion['empleador_nombre'] = $this->getRelationship('Empleador', 'nombre');
		$liquidacion['empleador_direccion'] = $this->getRelationship('Empleador', 'direccion');
		$liquidacion['convenio_categoria_convenio_id'] = $this->getRelationship('ConveniosCategoria', 'convenio_id');
		$liquidacion['convenio_categoria_nombre'] = $this->getRelationship('ConveniosCategoria', 'nombre');
		$liquidacion['convenio_categoria_costo'] = $this->getRelationship('ConveniosCategoria', 'costo');
		$liquidacion['convenio_categoria_jornada'] = $this->getRelationship('ConveniosCategoria', 'jornada');

		$totales['remunerativo'] = 0;
		$totales['no_remunerativo'] = 0;
		$totales['deduccion'] = 0;
		$totales['total_beneficios'] = 0;
		$totales['total_pesos'] = 0;
		$detalle = null;

		foreach ($this->__conceptos as $detalleLiquidacion) {
			$v = $this->__agregarDetalle($detalleLiquidacion);
			if (!empty($v)) {
				$detalle[] = $v;
			}

			if ($detalleLiquidacion['imprimir'] === "Si" || $detalleLiquidacion['imprimir'] === "Solo con valor") {

				$pago = "total_" . strtolower($detalleLiquidacion['pago']);
				switch ($detalleLiquidacion['tipo']) {
					case "Remunerativo":
						$totales[$pago] += $detalleLiquidacion['valor'];
						$totales['remunerativo'] += $detalleLiquidacion['valor'];
						break;
					case "No Remunerativo":
						$totales[$pago] += $detalleLiquidacion['valor'];
						$totales['no_remunerativo'] += $detalleLiquidacion['valor'];
						break;
					case "Deduccion":
						$totales[$pago] -= $detalleLiquidacion['valor'];
						$totales['deduccion'] += $detalleLiquidacion['valor'];
						break;
				}
			}
		}
		$totales['no_remunerativo'] -= $totales['total_beneficios'] ;
		$totales['total'] = $totales['remunerativo'] + $totales['no_remunerativo'] - $totales['deduccion'];

		/**
		 * Si a este empleador hay que aplicarle redondeo, lo hago y lo dejo expresado
		 * con el concepto redondeo en el detalle de la liquidacion.
		 */
		if ($this->getRelationship('Empleador', 'redondear') === 'Si') {
			$redondeo = round($totales['total']) - $totales['total'];
			if ($redondeo !== 0) {
				$opcionesFindConcepto['codigoConcepto'] = "redondeo";
				$conceptoRedondeo = $this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual',
						array(	'relacion' 			=> $this->getRelationship(),
								'codigoConcepto' 	=> 'redondeo'));
				$conceptoRedondeo['redondeo']['debug'] = "=" . round($totales['total']) . " - " . $totales['total'];
				$conceptoRedondeo['redondeo']['valor_cantidad'] = "0";

				/** Modify total */
				$totales['total'] += $redondeo;
				$totales['total_pesos'] += $redondeo;

				/**
				* Dependiendo del signo, lo meto como un concepto Remunerativo o una Deduccion.
				*/
				if ($redondeo > 0) {
					$totales['remunerativo'] += $redondeo;
					$conceptoRedondeo['redondeo']['tipo'] = 'No Remunerativo';
					$conceptoRedondeo['redondeo']['valor'] = $redondeo;
				} else {
					$totales['deduccion'] += $redondeo;
					$conceptoRedondeo['redondeo']['tipo'] = 'Deduccion';
					$conceptoRedondeo['redondeo']['valor'] = ($redondeo * -1);
				}
				$detalle[] = $this->__agregarDetalle($conceptoRedondeo['redondeo']);
			}
		}

		foreach (array('remunerativo', 'no_remunerativo', 'deduccion', 'total_pesos', 'total_beneficios', 'total') as $total) {
			$totales[$total] = number_format($totales[$total], 3, '.', '');
		}
		
		/**
		* Genero los pagos pendientes.
		* Diferencio en los diferentes tipos (beneficios o pesos).
		*/
		$auxiliar = null;
		$auxiliar['estado'] = 'Pendiente';
		$auxiliar['fecha'] = '##MACRO:fecha_liquidacion##';
		$auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
		$auxiliar['relacion_id'] = $liquidacion['relacion_id'];

		if ($totales['total_pesos'] > 0) {
			$auxiliar['monto'] = $totales['total_pesos'];
			$auxiliar['moneda'] = 'Pesos';
			$this->__setAuxiliar(array('save' => serialize($auxiliar), 'model' => 'Pago'));
		}

		if ($totales['total_beneficios'] > 0) {
			$auxiliar['monto'] = $totales['total_beneficios'];
			$auxiliar['moneda'] = "Beneficios";
			$this->__setAuxiliar(array('save' => serialize($auxiliar), 'model' => 'Pago'));
		}
		
		$save['Liquidacion']			= array_merge($liquidacion, $totales);
		$save['LiquidacionesDetalle']	= $detalle;
		
		$auxiliar = null;
		$auxiliar = $this->__getAuxiliar();
		if (!empty($auxiliar)) {
			$save['LiquidacionesAuxiliar'] = $auxiliar;
		}

		$error = null;
		$error = $this->__getError();
		if (!empty($error)) {
			$save['LiquidacionesError'] = $error;
		}
		
		$save['Liquidacion']			= array_merge($liquidacion, $totales);
		$save['LiquidacionesDetalle']	= $detalle;
		$this->create();
		return $this->saveAll($save);
	}


/**
* Esta funcion realiza el mapeo entre lo que tengo en el array de conceptos,
* y los datos que necesito para guardarlo en el detalle de la liquidacion.
*/
	function __agregarDetalle($detalleLiquidacion) {
		$detalle = null;
		if (!empty($detalleLiquidacion['concepto_id'])) {
			$detalle['concepto_id'] = $detalleLiquidacion['concepto_id'];
			$detalle['concepto_codigo'] = $detalleLiquidacion['codigo'];
			$detalle['concepto_nombre'] = $detalleLiquidacion['nombre'];
			$detalle['concepto_tipo'] = $detalleLiquidacion['tipo'];
			$detalle['concepto_periodo'] = $detalleLiquidacion['periodo'];
			$detalle['concepto_pago'] = $detalleLiquidacion['pago'];
			$detalle['concepto_imprimir'] = $detalleLiquidacion['imprimir'];
            $detalle['concepto_compone'] = $detalleLiquidacion['compone'];
            $detalle['concepto_remuneracion'] = $detalleLiquidacion['remuneracion'];
			$detalle['concepto_formula'] = $detalleLiquidacion['formula'] . ' ===>RES:' . $detalleLiquidacion['valor'];
			$detalle['concepto_cantidad'] = $detalleLiquidacion['cantidad'];
			$detalle['concepto_orden'] = $detalleLiquidacion['orden'];
			$detalle['coeficiente_id'] = $detalleLiquidacion['coeficiente_id'];
			$detalle['coeficiente_nombre'] = $detalleLiquidacion['coeficiente_nombre'];
			$detalle['coeficiente_tipo'] = $detalleLiquidacion['coeficiente_tipo'];
			$detalle['coeficiente_valor'] = $detalleLiquidacion['coeficiente_valor'];
			$detalle['debug'] = $detalleLiquidacion['debug'];
			$detalle['valor'] = $detalleLiquidacion['valor'];
			$detalle['valor_cantidad'] = $detalleLiquidacion['valor_cantidad'];
            $detalle['valor_unitario'] = $detalleLiquidacion['valor_unitario'];
		}
		return $detalle;
	}


/** Before performing a SUM, must be sure that I have all involved concepts */
    function __getAllNecessaryConcepts() {

        foreach ($this->__conceptos as $k => $conceptoTmp) {
            if (!isset($this->__conceptos[$k]['checked'])) {
                $this->__conceptos[$k]['checked'] = true;
                if (preg_match_all('/@([a-z0-9_]+)/', $conceptoTmp['formula'], $matchesTmp)) {
                    foreach ($matchesTmp[1] as $m) {
                        $allNecesaryConcepts[$m] = $m;
                    }
                }
            }
        }
        
        if (!empty($allNecesaryConcepts) && !empty($this->__conceptos)) {
            $diff = array_diff($allNecesaryConcepts, array_keys($this->__conceptos));
            if (!empty($diff)) {
                foreach ($diff as $concept) {
                    $tmpConcept = $this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual', array('relacion' => $this->getRelationship(), 'codigoConcepto' => $concept));
                    $tmpConcept[$concept]['imprimir'] = 'No';
                    $this->setConcept($tmpConcept);
                }
                $this->__getAllNecessaryConcepts();
            }
        }
    }

/**
* Dado un concepto, resuelve la formula.
*/
	function __getConceptValue($concepto) {
        //debug($concepto['nombre'] . ' ' . $concepto['formula']);
        $this->__setCurrentConcept($concepto);
        
        $valor = null;
		$errores = array();
		$formula = $concepto['formula'];
        
		/**
		* Si en la formula hay variables, busco primero estos valores.
		*/
		if (preg_match_all('/(#[a-z0-9_]+)/', $formula, $variablesTmp)) {

			foreach (array_unique($variablesTmp[1]) as $k=>$v) {
				/**
				* Debe buscar la variable para reemplazarla dentro de la formula.
				* Usa la RegEx y no str_replace, porque por ejemplo, si debo reemplzar #horas, y en cuentra
				* #horas lo hara ok, pero si encuentra #horas_enfermedad, dejara REEMPLAZO_enfermedad.
				*/
				$formula = preg_replace("/".$v."(\W)|".$v."$/", $this->getVarValue($v) . "$1", $formula);
			}
		}


		/**
		* Si en la cantidad hay una variable, la reemplazo.
		*/
		$conceptoCantidad = 0;
		if (!empty($concepto['cantidad'])) {
			if (isset($this->__variables[$concepto['cantidad']])) {
				$varValue = $this->getVarValue($concepto['cantidad']);
				if ($varValue !== '#N/A') {
					$conceptoCantidad = $varValue;
				} else {
					$this->__setError(array(
                        'tipo'					=> 'Variable No Resuelta',
          				'gravedad'				=> 'Media',
						'variable'				=> $concepto['cantidad'],
						'formula'				=> $concepto['formula'],
						'descripcion'			=> 'La cantidad intenta usar una variable que no ha podido ser resuelta.',
						'recomendacion'			=> 'Verifique que los datos hayan sido correctamente ingresados.',
						'descripcion_adicional'	=> ''));
				}
			} else {
				$this->__setError(array(
                        'tipo'					=> 'Variable Inexistente',
         				'gravedad'				=> 'Media',
						'variable'				=> $concepto['cantidad'],
						'formula'				=> $concepto['formula'],
						'descripcion'			=> 'La cantidad intenta usar una variable inexistente.',
						'recomendacion'			=> 'Verifique que la cantidad este correctamente definida y que la variable que la cantidad utiliza exista en el sistema.',
						'descripcion_adicional'	=> ''));
			}
		}

        /**
        * Si en el valor unitario hay una variable, la reemplazo.
        */
        $conceptoValorUnitario = 0;
        if (!empty($concepto['valor_unitario'])) {
            if (isset($this->__variables[$concepto['valor_unitario']])) {
                $varValue = $this->getVarValue($concepto['valor_unitario']);
                if ($varValue !== '#N/A') {
                    $conceptoValorUnitario = $varValue;
                } else {
                    $this->__setError(array(
                        'tipo'                  => 'Variable No Resuelta',
                        'gravedad'              => 'Media',
                        'variable'              => $concepto['valor_unitario'],
                        'formula'               => $concepto['formula'],
                        'descripcion'           => 'El valor unitario intenta usar una variable que no ha podido ser resuelta.',
                        'recomendacion'         => 'Verifique que los datos hayan sido correctamente ingresados.',
                        'descripcion_adicional' => ''));
                }
            } else {
                $this->__setError(array(
                        'tipo'                  => 'Variable Inexistente',
                        'gravedad'              => 'Media',
                        'variable'              => $concepto['valor_unitario'],
                        'formula'               => $concepto['formula'],
                        'descripcion'           => 'El valor unitario intenta usar una variable inexistente.',
                        'recomendacion'         => 'Verifique que la cantidad este correctamente definida y que la variable que la cantidad utiliza exista en el sistema.',
                        'descripcion_adicional' => ''));
            }
        }

		/**
		* Verifico si el nombre que se muestra del concepto es una formula, la resuelvo.
		*/
		if (!empty($concepto['nombre_formula'])) {
			$nombreConcepto = $concepto['nombre_formula'];
			
			/**
			* Si en el nombre hay variables, busco primero estos valores.
			*/
			if (preg_match_all("/(#[a-z0-9_]+)/", $nombreConcepto, $variablesTmp)) {
				foreach (array_unique($variablesTmp[1]) as $k=>$v) {
					/**
					* Debe buscar la variable para reemplazarla dentro de la formula.
					* Usa la RegEx y no str_replace, porque por ejemplo, si debo reemplzar #horas, y en cuentra
					* #horas lo hara ok, pero si encuentra #horas_enfermedad, dejara REEMPLAZO_enfermedad.
					*/
					$nombreConcepto = preg_replace("/".$v."(\W)|".$v."$/", $this->getVarValue($v) . "$1", $nombreConcepto);
				}
			}
			
			if (substr($nombreConcepto, 0, 3) === '=if') {
				$nombreConcepto = $this->resolver($nombreConcepto);
			} elseif (in_array(substr($nombreConcepto, 0, 1), array('#', '='))) {
				$nombreConcepto = substr($nombreConcepto, 1);
			}
		} else {
			$nombreConcepto = $concepto['nombre'];
		}

		/**
		* Veo si es una formula, hay un not, obtengo los conceptos y rearmo los formula eliminando la perte del not.
		*/
		if (preg_match('/not[\s]*\(([^()]+)\)/', $formula, $matches)) {
            //debug($concepto['codigo']);
			$conceptosNot = explode(',', str_replace('@', '', str_replace(' ', '', $matches[1])));
			$formula = str_replace('(,', '(', str_replace(str_replace(' ', '', $matches[0]), '', str_replace(' ', '', $formula)));
            //debug($conceptosNot);
		}
		

		/**
		* Veo si es una formula, que me indica la suma del remunerativo, de las deducciones o del no remunerativo.
		*/
		if (preg_match("/^=sum[\s]*\([\s]*(Remunerativo|Deduccion|No\sRemunerativo)[\s]*\)$/i", $formula, $matches)) {
			if (!isset($conceptosNot)) {
				$conceptosNot = array();
			}
            
            $valor = 0;
            $this->__getAllNecessaryConcepts();
			foreach ($this->__conceptos as $conceptoTmp) {
                
				if (!in_array($conceptoTmp['codigo'], $conceptosNot) && $conceptoTmp['tipo'] == $matches[1] && in_array($conceptoTmp['imprimir'], array('Si', 'Solo con valor'))) {
                    //debug($conceptoTmp['codigo']);
					if (empty($conceptoTmp['valor'])) {
						$resolucionCalculo = $this->__getConceptValue($conceptoTmp);
						$this->__conceptos[$conceptoTmp['codigo']] = array_merge($resolucionCalculo, $this->__conceptos[$conceptoTmp['codigo']]);
						$conceptoTmp['valor'] = $resolucionCalculo['valor'];
					}
					$valor += $conceptoTmp['valor'];
				}
			}
		}

		/**
		* Veo si es una formula, que tiene otros conceptos dentro.
		* Lo se porque los codigos de los conceptos empiezan siempre con @.
		*/
		elseif (substr($formula, 0, 1) === "=") {

			/**
			* Verifico que tenga calculado todos los conceptos que esta formula me pide.
			* Si aun no lo tengo, lo calculo.
			*/
			if (preg_match_all("/(@[\w]+)/", $formula, $matches)) {
                
                /** Must order array before replacing because of non exact replace of str_replace */
                $tmp = null;
                foreach (array_unique($matches[1]) as $match) {
                    $tmp[strlen($match)][] = $match;
                }
                krsort($tmp);
                foreach ($tmp as $tmpVals) {
                    foreach ($tmpVals as $t) {
                        $orderredMatches[] = $t;
                    }
                }
                
				foreach ($orderredMatches as $match) {
					$match = substr($match, 1);
					
					/** Si no esta, lo busco */
					if (!isset($this->__conceptos[$match])) {
						/**
						* Busco los conceptos que puedan estar faltandome.
						* Los agrego al array de conceptos identificandolos y poniendoles el estado a no imprimir.
						*/
						$conceptoParaCalculo = $this->Relacion->RelacionesConcepto->Concepto->findConceptos('ConceptoPuntual', array('relacion' => $this->getRelationship(), 'codigoConcepto' => $match));
						if (empty($conceptoParaCalculo)) {
							$this->__setError(array(	'tipo'					=> "Concepto Inexistente",
														'gravedad'				=> "Alta",
														'concepto'				=> $match,
														'variable'				=> "",
														'formula'				=> $formula,
														'descripcion'			=> "La formula requiere de un concepto inexistente.",
														'recomendacion'			=> "Verifique la formula y que todos los conceptos que esta utiliza existan.",
														'descripcion_adicional'	=> "verifique: " . $concepto['codigo']));
						} else {
							$conceptoParaCalculo[$match]['imprimir'] = 'No';
							$this->setConcept($conceptoParaCalculo);
						}
					}
					
					/** Si no tiene valor, lo calculo */
					if (!isset($this->__conceptos[$match]['valor'])) {
						if (isset($this->__conceptos[$match])) {
							$resolucionCalculo = $this->__getConceptValue($this->__conceptos[$match]);
							$this->__conceptos[$match] = array_merge($resolucionCalculo, $this->__conceptos[$match]);
						} else {
							$this->__setError(array(	"tipo"					=> "Concepto Inexistente",
														"gravedad"				=> "Alta",
														"concepto"				=> $match,
														"variable"				=> "",
														"formula"				=> $formula,
														"descripcion"			=> "La formula requiere de un concepto inexistente.",
														"recomendacion"			=> "Verifique la formula y que todos los conceptos que esta utiliza existan.",
														"descripcion_adicional"	=> "verifique: " . $concepto['codigo']));
						}
					}
						
					/**
					* Reemplazo en la formula el concepto por su valor.
					*/
					if (isset($this->__conceptos[$match])) {
						$resolucionCalculo['valor'] = $this->__conceptos[$match]['valor'];
                        $formula = str_replace('@' . $match, $resolucionCalculo['valor'], $formula);
						$resolucionCalculo['debug'] = $formula;
					} else {
						$this->__setError(array(	"tipo"					=> "Concepto Inexistente",
													"gravedad"				=> "Alta",
													"concepto"				=> $match,
													"variable"				=> "",
													"formula"				=> $formula,
													"descripcion"			=> "La formula requiere de un concepto inexistente.",
													"recomendacion"			=> "Verifique la formula y que todos los conceptos que esta utiliza existan.",
													"descripcion_adicional"	=> "verifique: " . $concepto['codigo']));
					}
				}
			}

			/** Resolv formula */
			$valor = $this->resolver($formula);
		} elseif (empty($formula)) {
			$this->__setError(array(	"tipo"					=> "Formula de Concepto Inexistente",
										"gravedad"				=> "Media",
										"concepto"				=> $concepto['codigo'],
										"variable"				=> "",
										"formula"				=> "",
										"descripcion"			=> "El concepto no tiene definida una formula.",
										"recomendacion"			=> "Ingrese la formula correspondiente al concepto en caso de que sea necesario. Para evitar este error ingrese como formula: =0",
										"descripcion_adicional"	=> "Se asume como 0 (cero) el valor del concepto."));
			$valor = 0;
		} else {
			$valor = '#N/A';
		}
		
		return array(
            'valor'             => $valor,
            'debug'             => $formula,
            'valor_cantidad'    => $conceptoCantidad,
            'valor_unitario'    => $conceptoValorUnitario,
            'nombre'            => $nombreConcepto,
            'errores' => $errores);
	}
	
	
/**
 * Busca el valor de una variable.
 *
 * @param string $variable El nombre de la variable que busco.
 * @return mixed El valor de la variable.
 * @access private.
 */
    function getVarValue($variable) {

        if (!isset($this->__variables[$variable])) {
            $this->__setError(array(    'tipo'                  => 'Variable Inexistente',
                                        'gravedad'              => 'Media',
                                        'variable'              => $variable,
                                        'descripcion'           => 'La formula intenta usar una variable inexistente. Se resolvera a "#N/A" su valor.',
                                        'recomendacion'         => 'Verifique que la formula este correctamente definida y que las variables que esta formula utiliza existan en el sistema.',
                                        'descripcion_adicional' => ''));
            $this->setVar($variable, '#N/A');
            return '#N/A';
        }
        /**
        * Ya he resuelto esta variable anteriormente.
        */
        elseif (isset($this->__variables[$variable]['valor'])) {
            return $this->__variables[$variable]['valor'];
        }
        /**
        * Intento resolverla.
        */
        else {
			
            /**
            * Si es una formula, la resuelvo.
            */
            if (substr($this->__variables[$variable]['formula'], 0, 1) === '=') {
                $formula = $this->__variables[$variable]['formula'];
                /**
                * Si en la formula hay variables, busco primero estos valores.
                */
                if (preg_match_all('/(#[a-z0-9_]+)/', $formula, $variables_tmp)) {
                    foreach (array_unique($variables_tmp[1]) as $v) {
                        $formula = preg_replace('/(' . $v . ')([\)\s\*\+\/\-\=\,]*(?!_))/', $this->getVarValue($v) . '$2', $formula);
                    }
                }
                if (preg_match_all("/@([\w]+)/", $formula, $conceptos_tmp)) {
                    foreach (array_unique($conceptos_tmp[1]) as $v) {
                        if (!empty($this->__conceptos[$v])) {
                            $tmp = $this->__getConceptValue($this->__conceptos[$v]);
                            $formula = preg_replace('/(@' . $v . ')([\)\s\*\+\/\-\=\,]*(?!_))/', $tmp['valor'] . '$2', $formula);
                        } else {
                            $this->__setError(array(    'tipo'                  => 'Concepto Inexistente',
                                                        'gravedad'              => 'Alta',
                                                        'concepto'              => '',
                                                        'variable'              => $variable,
                                                        'formula'               => $formula,
                                                        'descripcion'           => 'La formula intenta usar un concepto que no existe en la relacion.',
                                                        'recomendacion'         => 'Verifique que la relacion tenga cargados todos los datos necesarios.',
                                                        'descripcion_adicional' => ''));
                        }
                    }
                }

                $valor = $this->resolver($formula);
				//debug($variable . ' = ' . $valor . ' ( ' . $formula . ' )');
                
                if ($valor === '#N/A') {
                    $valor = 0;
                    $this->__setError(array(    'tipo'                  => 'Variable No Resuelta',
                                                'gravedad'              => 'Alta',
                                                'concepto'              => '',
                                                'variable'              => $variable,
                                                'formula'               => $this->__variables[$variable]['formula'],
                                                'descripcion'           => 'La formula intenta usar una variable que no es posible resolverla con los datos de la relacion.',
                                                'recomendacion'         => 'Verifique que la relacion tenga cargados todos los datos necesarios.',
                                                'descripcion_adicional' => ''));
                }
                $this->setVar($variable, $valor);
                return $valor;
            }
            
            
            /**
            * Busco si es una variable que viene dada por la relacion.
            * Depende de recursive, puede venir $data[model1][model2][campo] 0 $data[model1][campo]
            */
            if (preg_match('/^\[([a-zA-Z]*)\]\[([a-zA-Z]*)\]\[([a-zA-Z_]*)\]$/', $this->__variables[$variable]['formula'], $matchesA) || preg_match('/^\[([a-zA-Z]*)\]\[([a-zA-Z_]*)\]$/', $this->__variables[$variable]['formula'], $matchesB)) {
                $relationship = $this->getRelationship();
                if (isset($matchesA[1]) && isset($matchesA[2]) && isset($matchesA[3]) && Set::check($relationship, $matchesA[1] . '.' . $matchesA[2] . '.' . $matchesA[3])) {
                    $valor = $relationship[$matchesA[1]][$matchesA[2]][$matchesA[3]];
                } elseif (isset($matchesB[1]) && isset($matchesB[2]) && Set::check($relationship, $matchesB[1] . '.' . $matchesB[2])) {
                    $valor = $relationship[$matchesB[1]][$matchesB[2]];
                } else {
                    $this->__setError(array(
                        'tipo'                  => 'Variable No Resuelta',
                        'gravedad'              => 'Alta',
                        'variable'              => $variable,
                        'formula_variable'      => $this->__variables[$variable]['formula'],
                        'descripcion'           => 'La formula intenta usar una variable que no es posible resolverla con los datos de la relacion.',
                        'recomendacion'         => 'Verifique que la relacion tenga cargados todos los datos necesarios.',
                        'descripcion_adicional' => ''));
                    
                    $valor = 0;
                }
                
                switch($this->__variables[$variable]['formato']) {
                    case 'Minuscula':
                        $valor = strtolower($valor);
                    break;
                    case 'Mayuscula':
                        $valor = strtoupper($valor);
                    break;
                    case 'Primera Mayuscula':
                        $valor = ucfirst($valor);
                    break;
                }
                //debug($variable . ': ' .$valor);
                $this->setVar($variable, $valor);
                return $valor;
            }
            

            switch ($variable) {
                case '#mes_liquidacion':
					$return = $this->getPeriod('mes');
                break;
                case '#ano_liquidacion':
					$return = $this->getPeriod('ano');
                break;
                case '#periodo_liquidacion':
					$return = $this->getPeriod('periodo');
                break;
                case '#periodo_liquidacion_completo':
					$return = $this->getPeriod('periodoCompleto');
                break;
                case '#fecha_desde_liquidacion':
					$return = $this->getPeriod('desde');
                break;
                case '#fecha_hasta_liquidacion':
					$return = $this->getPeriod('hasta');
                break;
				default:
                    if (!empty($this->__variables[$variable]['formula'])) {
                        $this->__setError(array(
                            'tipo'                  => 'Variable No Resuelta',
                            'gravedad'              => 'Alta',
                            'variable'              => $variable,
                            'formula_variable'      => $this->__variables[$variable]['formula'],
                            'descripcion'           => 'La formula intenta usar una variable que no es posible resolver.',
                            'recomendacion'         => 'Verifique la formula de la variable.',
                            'descripcion_adicional' => ''));
                    }
                    $return = 0;
				break;
			}

			$this->setVar($variable, $return);
			return $return;
        }
    }
    

    function __setError($error) {
        $error['concepto'] = $this->__getCurrentConcept('codigo');
        if (empty($error['formula_concepto'])) {
            $error['formula_concepto'] = $this->__getCurrentConcept('formula');
        }
        $this->__receiptError[] = $error;
    }
    
    function __getError() {
        return $this->__receiptError;
    }

    function setPeriod($period) {
        /** Guess if setting just the year */
        if (is_numeric($period) && strlen($period) === 4) {
            $this->__period['ano'] = $period;
        } elseif (is_string($period)) {
            $this->__period = $this->format($period, 'periodo');
        } else {
			$this->__period = $period;
		}
    }
    
    function getPeriod($option = '') {
        
        if (isset($this->__period[$option])) {
            return $this->__period[$option];
        } elseif (empty($option)) {
            return $this->__period;
        } else {
            return '';
        }
    }

    function setRelationship($relationship) {
        $this->__relationship = $relationship;
    }

    function getRelationship($model = null, $field = null) {
		if (!is_null($model) && !is_null($field)) {
        	return $this->__relationship[$model][$field];
		} elseif (!is_null($model)) {
        	return $this->__relationship[$model];
		} else {
			return $this->__relationship;
		}
    }
    
    function setVar($var, $value = null) {
        if (is_string($var) && !is_null($value)) {
            $this->__variables[$var]['valor'] = $value;
        } else {
            foreach ($var as $varName => $varDefinition) {
                if (!is_array($varDefinition)) {
                    $tmp = $varDefinition;
                    $varDefinition = null;
                    $varDefinition['valor'] = $tmp;
                }
                $this->__variables[$varName] = $varDefinition;
            }
        }
    }
    
    
/**
 * Agrega datos que seran guardados en la tabla liquidaciones_auxiliares.
 *
 * @param array $auxiliar Los datos a guardar.
 * @return void.
 * @access private.
 */
    function __setAuxiliar($auxiliar) {
        if (!empty($auxiliar)) {
            if (!isset($auxiliar[0])) {
                $auxiliar = array($auxiliar);
            }
            if (empty($this->__saveAuxiliar)) {
                $this->__saveAuxiliar = $auxiliar;
            } else {
                $this->__saveAuxiliar = array_merge($this->__saveAuxiliar, $auxiliar);
            }
        }
    }

    
    function __getAuxiliar() {
        return $this->__saveAuxiliar;
    }
    

/**
 * Agrega conceptos al array de conceptos de su tipo.
 *
 * @param array $concepto Conceptos.
 * @param string $concepto El tipo de conceptos que se desea agregar.
 * @return void.
 * @access private.
 */
    function setConcept($conceptos) {
		if (!empty($conceptos)) {
			if (isset($conceptos[0])) {
				foreach ($conceptos as $concepto) {
					if (empty($this->__conceptos)) {
						$this->__conceptos = $concepto;
					} else {
						$this->__conceptos = array_merge($this->__conceptos, $concepto);
					}
				}
			} else {
				if (empty($this->__conceptos)) {
					$this->__conceptos = $conceptos;
				} else {
					$this->__conceptos = array_merge($this->__conceptos, $conceptos);
				}
			}
		}
    }


    function getConcept($conceptCode = null) {
        if (empty($conceptCode)) {
            return $this->__conceptos;
        } elseif (!empty($this->__conceptos[$conceptCode])) {
            return $this->__conceptos[$conceptCode];
        } else {
            return array();
        }
    }


    /**
    * Sets the concept been resolved.
    */
    function __setCurrentConcept($concept) {
        if (!$this->checkRecursivity($concept['codigo'])) {
            arsort($this->__recursivityCounter);
            foreach ($this->__recursivityCounter as $k => $v) {
                debug($v . ') => @' . $k . ': ' . $this->__conceptos[$k]['formula'] . ' ('.$this->__conceptos[$k]['valor'].')');
            }
            d('Corto por recursividad');
        }
        $this->__currentConcept = $concept;
    }

    function __getCurrentConcept($key = null) {
        if ($key === null) {
            return $this->__currentConcept;
        } else {
            return $this->__currentConcept[$key];
        }
    }
    

    function resetRecursivity() {
        $this->__recursivityCounter = null;
    }
    
    function checkRecursivity($match) {
        if (!isset($this->__recursivityCounter[$match])) {
            $this->__recursivityCounter[$match] = 1;
        } else {
            $this->__recursivityCounter[$match]++;
        }

        if ($this->__recursivityCounter[$match] >= 30) {
            //d($match . ' ' . $this->__recursivityCounter[$match]);
            return false;
        }
        return true;
    }
    
}
?>