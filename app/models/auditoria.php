<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las auditorias.
 * Cada operacion de escritura (add/edit) o eliminacion (delete) deja un log.
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
 * La clase encapsula la logica de acceso a datos asociada a las auditorias.
 *
 * @package		pragtico
 * @subpackage	app.models
 */
class Auditoria extends AppModel {

/**
 * Retorna la direccion IP de un cliente.
 *
 * @return string La direccion IP del cliente conectado.
 * @access private
 */
    function __getIp() {
    	if(getenv("HTTP_CLIENT_IP"))
        	return getenv("HTTP_CLIENT_IP"); 
    	elseif(getenv("HTTP_X_FORWARDED_FOR"))
			return getenv("HTTP_X_FORWARDED_FOR");
	else
		return getenv("REMOTE_ADDR");
    }


/**
 * Crea un nuevo registro de auditoria.
 *
 * @param array $data El array para ser guardado.
 * @return boolean True si se puedo crear el nuevo registro correctamente, false en otro caso.
 * @access public
 */
    function auditar($data) {
		$session = &new SessionComponent();
		if($session->check('__Usuario')) {
			$usuario = $session->read('__Usuario');
			$save['usuario'] = $usuario['Usuario']['nombre'];
		}
		else {
			$save['usuario'] = "publico";
		}
    	$save['ip'] = $this->__getIp();
		$save['data'] = serialize($data['data']);
		$save['tipo'] = $data['tipo'];
		$save['user_id'] = "1";
		$save['role_id'] = "1";
		$save['group_id'] = "0";
		$save['permissions'] = "256";
		$saveAuditoria['Auditoria'] = $save;
		$this->create($saveAuditoria);
		$this->save($saveAuditoria);
	}    
    
}
?>