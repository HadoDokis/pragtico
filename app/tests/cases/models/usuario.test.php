<?php
/**
 * Este archivo contiene un model generico (fake) para los casos de pruebas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.tests.models
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 54 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
App::import("Model", "Usuario");

/**
 * La clase para un para un caso de prueba generico (fake).
 *
 * @package app.tests
 * @subpackage app.tests.models
 */
class UsuarioTest extends Usuario {

	
/**
 * Indico en nombre del model.
 *
 * @var string
 * @access public
 */
	var $name = 'Usuario';
	
	
/**
 * Indico que use la conexion de prueba (test).
 *
 * @var string
 * @access public
 */
	var $useDbConfig = 'test_suite';

}
?>