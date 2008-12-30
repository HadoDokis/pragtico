<?php
/**
 * Este archivo contiene un caso de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.cases.components
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 54 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */


App::import('Component', 'Formulador');

require_once(APP . "tests" . DS . "cases" . DS . "controllers" . DS . "fake_test_controller.test.php");


/**
 * Caso de prueba para el Component Formulador.
 *
 * @package app.tests
 * @subpackage app.tests.cases.components
 */
class FormuladorComponentTestCase extends CakeTestCase {
	
/**
 * El component que probare.
 *
 * @var array
 * @access public
 */
    var $FormuladorComponentTest;

    
/**
 * Controller que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
    var $controller;
	
	
/**
 * El constructor de la clase.
 *
 * @access public
 */
	function __construct() {
    	$this->FormuladorComponentTest =& new FormuladorComponent();
    	$this->controller = new FakeTestController();
		$this->FormuladorComponentTest->startup(&$this->controller);
    }


	function testResolverNombreFormulas() {
		
		$formula = "=if ('mensual' = 'mensual', 'Basico', 'Horas')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Basico';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('Fondo Social'='N/A', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Fondo Social';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('Fondo Social'='Fondo Social', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Aporte Solidario';
		$this->assertEqual($expected, $result);
	
		$formula = "=if ('N/A'='N/A', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Aporte Solidario';
		$this->assertEqual($expected, $result);
	}
	
	
    function testResolverFechas() {

		$formula = '=if (month(date(2008, 11, 01)) = 11, 1, 0)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = '=date(2007, 12, 21)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1198195200';
		$this->assertEqual($expected, $result);

		$formula = '=datedif ("2007-12-18", "2007-12-22", "D")';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = '=datedif (date(2007, 12, 18), date(2007, 12, 22), "D")';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
	}

	
    function testResolverAlgebraica() {
    
		$formula = "=if ('ax'='ak', if ('j'='j', 3, 4), min(6,3)) + if ('uz'='uz', 1, 2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('1z'='2z', min(10,20), max(3,5))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '5';
		$this->assertEqual($expected, $result);

		$formula = "=min(2, if ('ax'='ax', 1, 8), 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
        
		$formula = "=1";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=1+1";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
		
		$formula = "=(1+1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);

		$formula = "=(1+1)+2";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=(1+1)*10";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '20';
		$this->assertEqual($expected, $result);
		
		$formula = "=(2*3)+10";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '16';
		$this->assertEqual($expected, $result);
		
		$formula = "=(2*3)+(4*4)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '22';
		$this->assertEqual($expected, $result);
		
		$formula = "=(10/2)+5+(2*3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '16';
		$this->assertEqual($expected, $result);
		
		$formula = "=((1+1)*5)/((2*2)+1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
	}

	function testResolverCondicional() {
		$formula = "=if ('9aaBB11'='9aaBB22', if ('s'='s', 1, 2), if ('s'='s', if (1=2, 2, 5), 10))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '5';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('9aaBB11'='9aaBB11', if ('s'='s', 1, 2), 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
	
		$formula = "=if ('aaBB11'='AAbb22', 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '0';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('aaBB11'='aaBB11', 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<>3, 1, 1+1+2*2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<>2, 1, 1+1+2*2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '6';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=2, (1+1+2)*2, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '8';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<4, 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2>2, 1, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '3';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=3, 1, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '3';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=2, 1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
	}		
	
	function testResolverStrings() {
		$formula = '=left("mi casa es verde", 2)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'mi';
		$this->assertEqual($expected, $result);
		
		$formula = '=right("mi casa es verde", 5)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'verde';
		$this->assertEqual($expected, $result);
	
		$formula = '=mid("mi casa es verde", 4, 4)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'casa';
		$this->assertEqual($expected, $result);
	
	}	

	function testResolverFuncionesDeGrupo() {

		$formula = "=max(2, 4, 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '6';
		$this->assertEqual($expected, $result);
		
		$formula = "=max(-2, -4, -6, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '0';
		$this->assertEqual($expected, $result);
		
		$formula = "=max(-2, -4, -6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '-2';
		$this->assertEqual($expected, $result);
		
		$formula = "=average(2, 4, 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(2, 4, 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(0, 2, 4, 6, -1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '-1';
		$this->assertEqual($expected, $result);
		
		$formula = "=sum(0, 2, 4, 6, -1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '11';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(10,20,30,2) + min(100,200,300) + min(200,400,600)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '302';
		$this->assertEqual($expected, $result);
		
		$formula = "=2 + min(10,20,30,2) + max(3,5,7) + sum(5,6,1) -3";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '20';
		$this->assertEqual($expected, $result);
	
		$formula = "=2 + min(10,20,30,2) + max(3,5,7) + sum(5,6,1) -2 + min(2,3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '23';
		$this->assertEqual($expected, $result);
	}	
	
}


?>