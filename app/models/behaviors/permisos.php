<?php
/**
 * Behavior que contiene el manejo de permisos a nivel registros (row level).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			practico
 * @subpackage		app.models
 * @since			Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Especifico todos los metodos que me garantizan que de manera automagica cada registro que es recuperado o
 * guardado, siempre contendra el usuario y el grupo correcto, como asi tambien los permisos.
 *
 * Me baso en la idea expuesta por:
 * http://www.xaprb.com/blog/2006/08/16/how-to-build-role-based-access-control-in-sql/
 *
 * @package		pragtico
 * @subpackage	app.models.behaviors
 */
class PermisosBehavior extends ModelBehavior {

/**
 * Los equivalentes numericos de los permisos para el dueno, el grupo o rol y los otros.
 *
 * @var array
 * @access private
 */
	private $__permisos = array(
		"owner_read"   => 256,
		"owner_write"  => 128,
		"owner_delete" => 64,
		"group_read"   => 32,
		"group_write"  => 16,
		"group_delete" => 8,
	   	"other_read"   => 4,
	   	"other_write"  => 2,
	   	"other_delete" => 1
	);


/**
 * Los datos del usuario logueado.
 *
 * @var array
 * @access private
 */
	private $__usuario = array();
	

/**
 * Constructor de la clase.
 *
 * Busco el usuario logueado en la session.
 * @return array Los datos del usuario logueado.
 * @access private.
 */
	function __construct() {
		$session = &new SessionComponent();
		if($session->check('__Usuario')) {
			$this->__usuario = $session->read('__Usuario');
		}
	}


/**
 * Before save callback
 * Asigno los permisos por defecto, en caso de ser un registro nuevo.
 *
 * @return boolean True si la operacion puede continua, false si debe abortarse.
 * @access public.
 */    
    function beforeSave(&$model) {

    	if(empty($model->id)) {
			if(!isset($model->data[$model->name]['user_id'])) {
    			$model->data[$model->name]['user_id'] = $this->__usuario['Usuario']['id'];
    		}
    		if(!isset($model->data[$model->name]['role_id'])) {
    			$model->data[$model->name]['role_id'] = $this->__usuario['Usuario']['roles'];
    		}
    		if(!isset($model->data[$model->name]['group_id'])) {
    			$model->data[$model->name]['group_id'] = $this->__usuario['Usuario']['preferencias']['grupo_default_id'];
    		}
    		if(!isset($model->data[$model->name]['permissions'])) {
    			$model->data[$model->name]['permissions'] = $model->getPermissions();
    		}
    	}
    	return true;
    }


/**
 * Una vez que recupero los datos, recorro el array y agrego los permisos (delete o write).
 *
 * @param array $results El array con datos recuperados desde una query (find).
 * @param array $usuario Array que ocntiene la informacion del usuario y grupo/s del usuario logueado.
 * @return array El array con los resultados de la query con los campos de permisos ya agregados a cada registro.
 * @access private
 */	 
	function __colocarPermisos($results, $usuario) {
		foreach($results as $k=>$v) {
			if(is_array($v)) {
				$results[$k] = $this->__colocarPermisos($v, $usuario);
			}
		}

		if(isset($results['user_id']) && isset($results['group_id']) && isset($results['permissions'])) {
			return am($results, array(	'write'=>$this->__puede($usuario, $results, "write"),
										'delete'=>$this->__puede($usuario, $results, "delete")));
		}
		else {
			return $results;
		}
	}


/**
 * Una vez que haya realizado una busqueda, a cada registro le agrego dos nuevos campos que
 * con una bandera booleana me indican si puedo escribis y/o borrar.
 *
 * @param object $model Model que usa este behavior.
 * @param array $results Los resultados que retorno alguna query.
 * @param boolean $primary Indica si este resultado viene de una query principal o de una query que
 *						   es generada por otra (recursive > 1)
 * @return array array $results Los resultados con los campos de permisos ya agregados a cada registro.
 * @access public
 */	
	function afterFind(&$model, $results, $primary = false) {
		if(!empty($this->__usuario)) {
			$results = $this->__colocarPermisos($results, $this->__usuario);
		}
		return $results;
	}


/**
 * Esta funcion indica si un usuario puede o no realizar un acceso sobre un registro dependiendo del
 * dueno, el grupo y rol y los demas (otros).
 *
 * Si se trata del user_id == 1 (el root) permito todo.
 * Si se trata del dueno del registro, verifico en funcion del los permisos del dueno y
 * retorno los permisos que correspondan al dueno.
 * Si el usuario forma parte de uno de los grupos del registro y al mismo tiempo forma parte de uno de los roles del
 * registo, retorno los permisos que correspondan al grupo.
 * En los otros casos donde el usuario no es ni el root, ni el dueno ni forma parte concurrentemente del grupo y rol
 * del registro, retorno los permisos correspondiente a los otros.
 */ 
	function __puede($usuario, $registro, $acceso) {
		/**
		* Verifico si es el root.
		*/
		if((int)$usuario['Usuario']['id'] === 1) {
			return true;
		}
		
		/**
		* Verifico lo que puede hacer el dueno.
		*/
		if(($usuario['Usuario']['id'] === $registro['user_id'])
			&& ((int)$registro['permissions'] & (int)$this->__permisos['owner_' . $acceso])) {
			return true;
		}

		/**
		* Verifico lo que pueden hacer el grupo en funcion del rol.
		*/
		if((((int)$usuario['Usuario']['grupos'] & (int)$registro['group_id'])
			&& ((int)$registro['permissions'] & (int)$this->__permisos['group_' . $acceso])) &&
		   (((int)$usuario['Usuario']['roles'] & (int)$registro['role_id'])
			&& ((int)$registro['permissions'] & (int)$this->__permisos['group_' . $acceso]))) {
			return true;
		}

		/**
		* Verifico lo que pueden hacer los otros.
		*/
		if((int)$registro['permissions'] & (int)$this->__permisos['other_' . $acceso]) {
			return true;
		}
		return false;
	}


/**
 * Antes de realizar cualquier busqueda, agrega las condiciones correspondientes a los permisos de cada usuario/grupo.
 *
 * La unica posibilidad de que este metodo no agregue las condiciones de seguridad, es que explitamente vengan
 * seteadas del codigo del programa alguna de estas condiciones:
 * 					- $queryData['conditions']['checkSecurity'] = false;
 * 					- $queryData['checkSecurity'] = false;
 *
 * @param object $model Model que usa este behavior.
 * @param array $queryData Data utilizada para ejecutar la query, ej: conditions, order, group, etc.
 * @return array $queryData Data utilizada para ejecutar la query con las condiciones modificadas.
 * @access public
 */
	function beforeFind(&$model, $queryData) {

		if(isset($queryData['checkSecurity'])) {
			$queryData['conditions']['checkSecurity'] = $queryData['checkSecurity'];
			unset($queryData['checkSecurity']);
		}
		else if(!isset($queryData['conditions']['checkSecurity'])) {
			$queryData['conditions']['checkSecurity'] = "read";
		}

		$checkSecurity = $queryData['conditions']['checkSecurity'];
		unset($queryData['conditions']['checkSecurity']);

		/**
		* La unica posibilidad de no chequear la seguridad, es que me venga explicitamente especificado no hacerlo.
		*/
		if($checkSecurity === false) {
			unset($queryData['conditions']['checkSecurity']);
			return $queryData;
		}

		/**
		*Verifico que se trate de alguno de los unicos 3 metodos soportados.
		*/
		if(in_array($checkSecurity, array("read", "write", "delete"))) {
			$seguridad = $this->__generarCondicionSeguridad($checkSecurity, $model->name);
		}
		else {
			trigger_error("Metodo de seguridad no soportado.", E_USER_ERROR);
		}

		if(!empty($seguridad)) {
			$queryData['conditions'] = array_merge($queryData['conditions'], $seguridad);
		}
		return $queryData;
	}


/**
 * Genera las condiciones de seguridad.
 *
 * @param object &$model Model que utiliza este behavior.
 * @param string $acceso Tipo de acceso que se desea realizar. Solo hay tres tipos permitidos:
 *						- read
 *						- write
 *						- delete
 * @return array Vacio si se trata de un usuario cuyo grupo primario sea el grupo de administradores donde
 * no se chequea seguridad. Array con las condiciones en cualquier otro caso.
 * @access public
 */
	function generarCondicionSeguridad(&$model, $acceso) {
		return $this->__generarCondicionSeguridad($acceso, $model->name);
	}

	
/**
 * Genera las condiciones de seguridad.
 *
 * @param string $acceso Tipo de acceso que se desea realizar. Solo hay tres tipos permitidos:
 *						- read
 *						- write
 *						- delete
 * @param string $modelName Nombre del model.
 * @return array Vacio si se trata de un usuario cuyo grupo primario sea el grupo de administradores donde
 * no se chequea seguridad. Array con las condiciones en cualquier otro caso.
 * @access private
 */
	function __generarCondicionSeguridad($acceso, $modelName) {
		
		$usuarioId = $this->__usuario['Usuario']['id'];
		$grupos = $this->__usuario['Usuario']['preferencias']['grupos_seleccionados'];
		$roles = $this->__usuario['Usuario']['roles'];
		

		/**
		* Si se trata de un usuario perteneciente al rol administradores, que no tiene grupo (root), no verifico permisos.
		*/
		if(empty($this->__usuario['Grupo']) && (int)$this->__usuario['Usuario']['roles'] & 1) {
			return array();
		}
		else {
			$seguridad['OR'][] =
				array("AND" => array(
					array(
						$modelName . ".user_id" => $usuarioId,
						"(" . $modelName . ".permissions) & " . $this->__permisos['owner_' . $acceso] => $this->__permisos['owner_' . $acceso]
					),
					array(
						"(" . $modelName . ".group_id) & " . $grupos . " >" => 0,
						"(" . $modelName . ".permissions) & " . $this->__permisos['group_' . $acceso] => $this->__permisos['group_' . $acceso]
					)
				));
			
			$seguridad['OR'][] =
				array("AND" => array(
					array(
						"(" . $modelName . ".role_id) & " . $roles . " >" => 0,
						"(" . $modelName . ".permissions) & " . $this->__permisos['group_' . $acceso] => $this->__permisos['group_' . $acceso]
					),
					array(
						"(" . $modelName . ".group_id) & " . $grupos . " >" => 0,
						"(" . $modelName . ".permissions) & " . $this->__permisos['group_' . $acceso] => $this->__permisos['group_' . $acceso]
					)
				));
			
			$seguridad['OR'][] =
				array(
					"(" . $modelName . ".permissions) & " . $this->__permisos['other_' . $acceso] => $this->__permisos['other_' . $acceso]
				);
		}

		/**
		* Cuando hago un delete, con los permisos solo del dueno es suficiente, por lo que los del grupo los quito.
		*/
		if($acceso == "delete") {
			unset($seguridad['OR'][0]['AND'][1]);
		}
		return $seguridad;
	}


/**
 * After save callback
 * Dejo un log en la auditoria del registro creado o modificado.
 *
 * @param boolen $created Indica si se trata de un nuevo registro (add) o una modificacion (update).
 * @return void.
 * @access public.
 */    
	function afterSave(&$model, $created) {
		/**
		* Evito que entre en loop infinito.
		*/
		if($model->name !== "Auditoria") {
			App::import("model", "Auditoria");
			$Auditoria = new Auditoria();
			$save['data'] = $model->data;
			if($created === true) {
				$save['tipo'] = "Alta";
			}
			else {
				$save['tipo'] = "Modificacion";
			}
			$Auditoria->auditar($save);
		}
		return true;
	}


/**
 * After delete callback
 * Dejo un log en la auditoria del registro eliminado.
 *
 * @return void.
 * @access public.
 */
	function afterDelete(&$model) {
		App::import("model", "Auditoria");
		$Auditoria = new Auditoria();
		$save['data'] = array($model->name => array($model->primaryKey => $model->id));
		$save['tipo'] = "Baja";
		$Auditoria->auditar($save);
	}
	
}
?>