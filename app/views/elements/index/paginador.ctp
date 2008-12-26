<?php

/**
* Creo un bloque con el paginador.
* Lo divido al paginador en navegacion (las flechitas) y la posicion, registro (4 de 10).
*/
$options = array();
foreach(array('named', 'pass') as $nombre) {
	if(!empty($this->params[$nombre])) {
		unset($this->params[$nombre]['direction']);
		unset($this->params[$nombre]['sort']);
		unset($this->params[$nombre]['page']);
		$options = array_merge($options, $this->params[$nombre]);
	}
}

$bloque_paginador[] = $formulario->tag('div', $paginador->paginador('navegacion', array('url'=> $options)), array('class' => 'navegacion'));
$bloque_paginador[] = $formulario->tag('div', $paginador->paginador('posicion'), array('class' => 'posicion'));


/**
* Si hay algun registro, muestro el 'mostrar'.
*/
if(isset($paginador->params['paging'][Inflector::classify($paginador->params['controller'])]['count']) 
   	&& $paginador->params['paging'][Inflector::classify($paginador->params['controller'])]['count'] > 0) {
	
	foreach (array(15, 25, 50) as $value) {
		$show[$value] = $formulario->link($value, array_merge($options, array('filas_por_pagina'=> $value)), array('title'=>sprintf(__('Show %s records', true), $value)));
	}
	
	$show[1000] = $formulario->link(__('A', true), array_merge($options, array('filas_por_pagina' => '1000')), array('title'=>__('Show all records', true)));
	$cantidadActual = $this->params['paging'][Inflector::classify($this->name)]['options']['limit'];
	if ($cantidadActual < 1000) {
		$show[$cantidadActual] = $formulario->tag('span', $cantidadActual);
	} else {
		$show[$cantidadActual] = $formulario->tag('span', __('A', true));
	}
	$bloque_paginador[] = $formulario->tag('div', __('Show', true) . ': ' . implode('/', $show), array('class' => 'cantidad_a_mostrar'));
}

echo implode('', $bloque_paginador);
?>