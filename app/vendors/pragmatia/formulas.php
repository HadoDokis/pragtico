<?php
/**
 * Resolv formulas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.vendors.pragmatia
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 267 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-16 13:24:25 -0200 (lun, 16 feb 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
 set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
/** PHPExcel_Calculation */
 App::import('Vendor', 'Calculation', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'Calculation.php');

class MyPHPExcel_Calculation extends PHPExcel_Calculation {

	protected function _raiseFormulaError($errorMessage) {
		$this->formulaError = $errorMessage;
	}

}


/**
 * Resolv formulas Class.
 *
 * @package     pragtico
 * @subpackage  app.vendors.pragmatia
 */
class Formulas {

	
/**
 * PHPExcel object.
 *
 * @var object
 * @access private
 */
	private $__objPHPExcel = null;
	private $__PHPExcel_Calculation = null;
	
	
/**
 * Used cells.
 *
 * @var array
 * @access private
 */	
	private $__cellId = 0;


/**
 * Instance PHPExcel's object.
 *
 * @return void
 * @access public
 */
    function __construct() {
		$this->__PHPExcel_Calculation = new MyPHPExcel_Calculation();
		//$this->__PHPExcel_Calculation->suppressFormulaErrors = true;
	}


	function checkFormula($formula, $returnError = true) {
		//$this->__PHPExcel_Calculation->suppressFormulaErrors = true;
		$this->formulaError = null;
		//debug($formula);
		$this->__PHPExcel_Calculation->parseFormula($formula);
		//debug($this->formulaError);
		if (empty($this->formulaError)) {
			return true;
		} elseif ($returnError) {
			return $this->formulaError;
		} else {
			return false;
		}
	}


/**
 * Remove spaces and "strange" characters from the formula.
 *
 * @param string $formula. The formula to be resolved.
 * @return string The ready to use formula.
 * @access private
 */
	function __cleanUp($formula) {
		/** Replace spaces in formulas to unify criterias.*/
		$formula = preg_replace('/\s*([=|<|>|,|\(|\)])\s*/', '$1', $formula);
		$formula = preg_replace('/(\d)\s{0,}\/\s{0,}(\d)/', '$1/$2', $formula);
		$formula = preg_replace('/[^[:print:]]/', '', $formula);
		if (substr($formula, 0, 1) !== '=') {
			$formula = '=' . $formula;
		} elseif (substr($formula, 0, 2) === '==') {
            $formula = substr($formula, 1);
        }
		return $formula;
	}


/**
 * Resolv the formula.
 *
 * @param string $formula. The formula to resolv.
 * @return mixed The result of the resolved formula.
 * @access public
 */
	function resolver($formula) {

		$this->formulaError = null;
		$cellId = 0;
		$formula = $this->__cleanUp($formula);
        $formula = preg_replace("/isblank\(\'?0000\-00\-00\'?\)/", 'true', $formula);
        $formula = preg_replace("/isblank\(\'?\d\d\d\d\-\d\d\-\d\d\'?\)/", 'false', $formula);
        
		if (preg_match_all("/date\(\'?(\d\d\d\d)-(\d\d)-(\d\d)\'?\)/", $formula, $strings)) {
            foreach (array_unique($strings[0]) as $k => $string) {
                $formula = str_replace($string, sprintf('date(%s, %s, %s)', $strings[1][$k], $strings[2][$k], $strings[3][$k]), $formula);
            }
		} elseif (preg_match_all("/\'?(?!\")(\d\d\d\d)-(\d\d)-(\d\d)\'?(?!\")/", $formula, $strings)) {
            foreach (array_unique($strings[0]) as $k => $string) {
                $formula = str_replace($string, sprintf('date(%s,%s,%s)', $strings[1][$k], $strings[2][$k], $strings[3][$k]), $formula);
            }
		}

		$this->__cellId++;
		$formula = str_replace('\'', '"', $formula);
        $formula = str_replace('#N/E', '0', $formula);
        $formula = str_replace('#VALUE!', '0', $formula);
        $formula = str_replace('#N/A', '0', $formula);
        $formula = str_replace('#NUM!', '0', $formula);
        $formula = str_replace('#DIV/0!', '0', $formula);

//		$formula = "= if(or('M'='1S', 'M'='2S', 'normal' = 'final'), 0, (if('normal' = 'vacaciones', 0, (0 + 0 + 0 +0 + 0)) * 0.005))";
//		$formula = $this->__cleanUp($formula);
//debug($formula);
//		debug($this->checkFormula($formula));
//d($this->__PHPExcel_Calculation->calculateFormula($formula));
		return $this->__PHPExcel_Calculation->calculateFormula($formula);
	}

}