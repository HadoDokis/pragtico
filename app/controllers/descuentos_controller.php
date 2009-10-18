<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los descuentos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision$
 * @modifiedby      $LastChangedBy$
 * @lastmodified    $Date$
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los descuentos.
 *
 * Se refiere a los posibles descuentos que puede tener un trabajador de una relacion laboral,
 * puede ser un vale, un embargo, un prestamo, etc.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class DescuentosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Descuento.alta' => 'desc'
        )
    );

    var $helpers = array('Documento');


    function reporte_vales_prestamos() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Descuento.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Relacion.empleador_id'] = $this->data['Condicion']['Bar-empleador_id'];
            }
            
            if (!empty($this->data['Condicion']['Bar-estado'])) {
                $conditions['Descuento.estado'] = $this->data['Condicion']['Bar-estado'];
            }
            
            if (!empty($this->data['Condicion']['Bar-desde'])) {
                $conditions['Descuento.desde >='] = $this->data['Condicion']['Bar-desde'];
            }
            
            if (!empty($this->data['Condicion']['Bar-hasta'])) {
                $conditions['Descuento.desde <='] = $this->data['Condicion']['Bar-hasta'];
            }
            
            if (!empty($this->data['Condicion']['Bar-tipo'])) {
                $conditions['Descuento.tipo'] = $this->data['Condicion']['Bar-tipo'];
            } else {
                $conditions['Descuento.tipo'] = array('Vale', 'Prestamo');
            }
            
            $this->Descuento->Behaviors->detach('Permisos');
            $this->Descuento->contain(array(
                'DescuentosDetalle',
                'Relacion' => array('Empleador', 'Trabajador',
                    'order' => array('Relacion.empleador_id', 'Relacion.trabajador_id'))));
            //d($this->Descuento->find('all', array('conditions' => $conditions)));
            $this->set('data', $this->Descuento->find('all', array('conditions' => $conditions)));
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }
        
    
/**
 * detalles.
 * Muestra via desglose los detalles de un descuento.
 */
	function detalles($id) {
		$this->Descuento->contain("DescuentosDetalle");
		$this->data = $this->Descuento->read(null, $id);
	}

}
?>