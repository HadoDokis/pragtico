<?php
/**
 * Este archivo contiene los datos de un fixture para los casos de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.tests.fixtures
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 54 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase para un fixture para un caso de prueba.
 *
 * @package app.tests
 * @subpackage app.tests.fixtures
 */
class LiquidacionesAuxiliarFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'LiquidacionesAuxiliar';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'liquidacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'save' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '250'),
        'model' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'created' => array('type' => 'datetime', 'null' => false),
        'modified' => array('type' => 'datetime', 'null' => false),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'role_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'permissions' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
    );


/**
 * Los registros.
 *
 * @var array
 * @access public
 */
    var $records = array(
        array(
            'id' => '1',
            'liquidacion_id' => '1',
            'save' => 'a:3:{s:2:"id";s:1:"1";s:6:"estado";s:9:"Liquidada";s:14:"liquidacion_id";s:24:"##MACRO:liquidacion_id##";}',
            'model' => 'Hora',
            'created' => '2008-10-30 10:55:14',
            'modified' => '2008-10-30 10:55:14',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'liquidacion_id' => '2',
            'save' => 'a:3:{s:2:"id";s:1:"2";s:6:"estado";s:9:"Liquidada";s:14:"liquidacion_id";s:24:"##MACRO:liquidacion_id##";}',
            'model' => 'Hora',
            'created' => '2008-10-30 10:55:14',
            'modified' => '2008-10-30 10:55:14',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
    );
}

?>