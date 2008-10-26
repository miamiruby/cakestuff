<?php
/* SVN FILE: $Id: configure.group.php 7118 2008-06-04 20:49:29Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.groups
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 7118 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-06-04 16:49:29 -0400 (Wed, 04 Jun 2008) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/** ConfigureGroupTest
 *
 * This test group will run all test for the configure and loader.
 *
 * @package    cake.tests
 * @subpackage cake.tests.groups
 */
/**
 * ConfigureGroupTest class
 * 
 * @package              cake
 * @subpackage           cake.tests.groups
 */
class ConfigureGroupTest extends GroupTest {
/**
 * label property
 * 
 * @var string 'Configure, Loader, ClassRegistry Tests'
 * @access public
 */
	var $label = 'Configure, Loader, ClassRegistry Tests';
/**
 * ConfigureGroupTest method
 * 
 * @access public
 * @return void
 */
	function ConfigureGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'configure');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'class_registry');
	}
}
?>
