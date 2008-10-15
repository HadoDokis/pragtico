<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las provincias.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.controllers
 * @since			Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las provincias.
 *
 * @package		pragtico
 * @subpackage	app.controllers
 */
class ProvinciasController extends AppController {

/**
 * localidades.
 * Muestra via desglose las localidades relacionadas a esta provincia.
 */
	function localidades($id) {
		$this->Provincia->contain(array("Localidad"));
		$this->data = $this->Provincia->read(null, $id);
	}
}
?>