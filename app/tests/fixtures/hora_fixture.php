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
class HoraFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Hora';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'relacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'liquidacion_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'periodo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '8'),
        'cantidad' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'tipo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '25'),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Pendiente', 'length' => '10'),
        'observacion' => array('type' => 'text', 'null' => false, 'default' => ''),
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
            'relacion_id' => '1',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '57.00',
            'tipo' => 'Normal',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 10:55:22',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '54.00',
            'tipo' => 'Normal',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '3',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '53.00',
            'tipo' => 'Normal',
            'estado' => 'Pendiente',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '4',
            'relacion_id' => '2',
            'liquidacion_id' => '1',
            'periodo' => '2008092Q',
            'cantidad' => '53.00',
            'tipo' => 'Normal',
            'estado' => 'Liquidada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '5',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '30.00',
            'tipo' => 'Extra 50%',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '6',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '10.00',
            'tipo' => 'Extra 100%',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '7',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '57.00',
            'tipo' => 'Normal',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 10:55:22',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '8',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '54.00',
            'tipo' => 'Normal',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '9',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '53.00',
            'tipo' => 'Normal',
            'estado' => 'Pendiente',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '10',
            'relacion_id' => '2',
            'liquidacion_id' => '1',
            'periodo' => '2008092Q',
            'cantidad' => '53.00',
            'tipo' => 'Normal',
            'estado' => 'Liquidada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '11',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '30.00',
            'tipo' => 'Extra 50%',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '12',
            'relacion_id' => '2',
            'liquidacion_id' => null,
            'periodo' => '2008092Q',
            'cantidad' => '10.00',
            'tipo' => 'Extra 100%',
            'estado' => 'Confirmada',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-30 02:00:46',
            'modified' => '2008-10-30 02:00:46',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        )
    );
}

?>