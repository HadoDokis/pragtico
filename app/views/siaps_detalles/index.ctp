<?php
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Siap-version'] = array("options"=>"listable", "model"=>"Siap", "displayField"=>array("Siap.version"), "empty"=>true);
$condiciones['Condicion.SiapsDetalle-elemento'] = array();
$condiciones['Condicion.SiapsDetalle-descripcion'] = array();
$fieldsets[] = array("campos"=>$condiciones);
$fieldset = $formulario->pintarFieldsets($fieldsets, array("fieldset"=>array("legend"=>"Detalle de Siap", "imagen"=>"siap_detalle.gif")));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k=>$v) {
	$fila = null;
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"id", "valor"=>$v['SiapsDetalle']['id'], "write"=>$v['SiapsDetalle']['write'], "delete"=>$v['SiapsDetalle']['delete']);
	$fila[] = array("model"=>"Siap", "field"=>"version", "valor"=>$v['Siap']['version']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"elemento", "valor"=>$v['SiapsDetalle']['elemento']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"descripcion", "valor"=>$v['SiapsDetalle']['descripcion']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"desde", "valor"=>$v['SiapsDetalle']['desde']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"longitud", "valor"=>$v['SiapsDetalle']['longitud']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"valor", "valor"=>$v['SiapsDetalle']['valor']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"direccion_relleno", "valor"=>$v['SiapsDetalle']['direccion_relleno']);
	$fila[] = array("model"=>"SiapsDetalle", "field"=>"caracter_relleno", "valor"=>$v['SiapsDetalle']['caracter_relleno']);
	$cuerpo[] = $fila;
}
echo $this->renderElement("index/index", array("condiciones"=>$fieldset, "cuerpo"=>$cuerpo));

?>