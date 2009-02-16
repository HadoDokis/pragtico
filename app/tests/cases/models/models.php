<?php
/**
 * Este archivo contiene un model generico (fake) para los casos de pruebas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 54 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
 require_once(APP . 'app_model.php');
 
class Article extends AppModel {
    
    var $name = 'Article';
    var $useTable = 'articles';
    var $useDbConfig = 'test';

/**
 * hasMany property
 *
 * @var array
 * @access public
 */
    //var $hasMany = array('Comment' => array('dependent' => true, 'unique' => true));
    var $hasMany = array('Comment' => array('dependent' => true));
    //var $hasMany = array('Comment');
    
} 


/**
 * Comment class
 *
 * @package       cake.tests
 * @subpackage    cake.tests.cases.libs.model
 */
class Comment extends AppModel {
    var $name = 'Comment';
    var $useTable = 'comments';
    var $useDbConfig = 'test';
    
/**
 * name property
 *
 * @var string 'Comment'
 * @access public
 */

/**
 * belongsTo property
 *
 * @var array
 * @access public
 */
    var $belongsTo = array('Article');
}

?>