<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los bancos.
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
 * La clase encapsula la logica de acceso a datos asociada a los bancos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Banco extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
	var $modificadores = array('index'	=> array('contain' => array()),
								'edit'	=> array('contain' => array('Sucursal')));
	
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre del banco.')
        ),
        'codigo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el codigo del banco.'),
			array(
				'rule'		=> VALID_NUMBER, 
				'message'	=> 'El codigo del banco debe ser un numerico.')
        )
	);

	var $breadCrumb = array('format' => '(%s) %s', 
							'fields' => array('Banco.codigo', 'Banco.nombre'));
	
	var $hasMany = array(	'Sucursal' =>
                        array('className'    => 'Sucursal',
							  'dependent'	 => true));

}
?>