<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las actividades.
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
 * La clase encapsula la logica de acceso a datos asociada a las actividades.
 *
 * Se refiere a las actividades propuestas por AFIP en SIAP.
 * Contiene tanto las actividades para Trabajadores como para Empleadores.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Actividad extends AppModel {

    protected $__permissions = '484';
    
	var $validate = array(
        'codigo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el codigo de la actividad.')
        ),
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre de la actividad.')
        ),
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el tipo de la actividad.')
        )
	);

    var $breadCrumb = array('format'    => '%s - %s',
                            'fields'    => array('Actividad.codigo', 'Actividad.nombre'));

}
?>