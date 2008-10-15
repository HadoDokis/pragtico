<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la ropa
 * que se le entrega a un trabajador de una relacion laboral.
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
 * La clase encapsula la logica de acceso a datos asociada a la ropa
 * que se le entrega a un trabajador de una relacion laboral.
 *
 * @package		pragtico
 * @subpackage	app.models
 */
class Ropa extends AppModel {

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array(	"index"=>array(	"contain"=>array("Relacion.Trabajador",
																"Relacion.Empleador")),
								"add" =>array(	"valoresDefault"=>array("fecha"=>"date('d/m/Y')")),
								"edit"=>array(	"contain"=>array("Relacion.Trabajador",
																"Relacion.Empleador",
																"RopasDetalle")));
	
	var $validate = array(
        'relacion_id__' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY, 
				'message'	=>'Debe seleccionar la relacion laboral.')
        ),
        'fecha' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY, 
				'message'	=>'Debe ingresar una fecha.'),
			array(
				'rule'	=> VALID_DATE,
				'message'	=>'Debe ingresar un fecha valida o seleccionarla del calendario.'),
        ));
	
	var $belongsTo = array(	'Relacion' =>
                        array('className'    => 'Relacion',
                              'foreignKey'   => 'relacion_id'));

	var $hasMany = array(	'RopasDetalle' =>
                        array('className'    => 'RopasDetalle',
                              'foreignKey'   => 'ropa_id'));

}
?>
