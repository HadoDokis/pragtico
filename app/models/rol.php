<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los roles (seguridad).
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
 * La clase encapsula la logica de acceso a datos asociada a los roles (seguridad).
 *
 * @package		pragtico
 * @subpackage	app.models
 */
class Rol extends AppModel {

	var $order = array('Rol.nombre'=>'asc');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY, 
				'message'	=>'Debe especificar el nombre del rol.')
        )
	);

	var $hasAndBelongsToMany = array(	'Menu' =>
						array('with' => 'RolesMenu'),
										'Usuario' =>
						array('with' => 'RolesUsuario'));
						

	function beforeSave() {
		/**
		* Es un add.
		* Como uso matematica binaria, el proximo ID debe ser generado por mi como potencia de 2 del anterior.
		*/
		if(empty($this->data['Rol']['id'])) {
			$this->recursive = -1;
			$rol = $this->find("first", array("checkSecurity"=>false, "fields"=>array("MAX(Rol.id) AS maximo")));
			$ultimoRol = $rol[0]['maximo'];
			$this->data['Rol']['id'] = $ultimoRol * 2;
		}
		return parent::beforeSave();
	}


}
?>