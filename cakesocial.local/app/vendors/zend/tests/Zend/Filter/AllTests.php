<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AllTests.php 10637 2008-08-04 16:20:40Z ralph $
 */


if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Filter_AllTests::main');
}


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * @see Zend_Filter_AlnumTest
 */
require_once 'Zend/Filter/AlnumTest.php';

/**
 * @see Zend_Filter_AlphaTest
 */
require_once 'Zend/Filter/AlphaTest.php';

/**
 * @see Zend_Filter_BaseNameTest
 */
require_once 'Zend/Filter/BaseNameTest.php';

/**
 * @see Zend_Filter_DigitsTest
 */
require_once 'Zend/Filter/DigitsTest.php';

/**
 * @see Zend_Filter_DirTest
 */
require_once 'Zend/Filter/DirTest.php';

/**
 * @see Zend_Filter_HtmlEntitiesTest
 */
require_once 'Zend/Filter/HtmlEntitiesTest.php';

/**
 * @see Zend_Filter_InflectorTest
 */
require_once 'Zend/Filter/InflectorTest.php';

/**
 * @see Zend_Filter_IntTest
 */
require_once 'Zend/Filter/IntTest.php';

/**
 * @see Zend_Filter_PregReplaceTest
 */
require_once 'Zend/Filter/PregReplaceTest.php';

/**
 * @see Zend_Filter_RealPathTest
 */
require_once 'Zend/Filter/RealPathTest.php';

/**
 * @see Zend_Filter_StringToLowerTest
 */
require_once 'Zend/Filter/StringToLowerTest.php';

/**
 * @see Zend_Filter_StringToUpperTest
 */
require_once 'Zend/Filter/StringToUpperTest.php';

/**
 * @see Zend_Filter_StringTrimTest
 */
require_once 'Zend/Filter/StringTrimTest.php';

/**
 * @see Zend_Filter_StripNewlinesTest
 */
require_once 'Zend/Filter/StripNewlinesTest.php';

/**
 * @see Zend_Filter_StripTagsTest
 */
require_once 'Zend/Filter/StripTagsTest.php';

/**
 * @see Zend_Filter_InputTest
 */
require_once 'Zend/Filter/InputTest.php';

/**
 * Word filter tests
 */
require_once 'Zend/Filter/Word/CamelCaseToDashTest.php';
require_once 'Zend/Filter/Word/CamelCaseToSeparatorTest.php';
require_once 'Zend/Filter/Word/CamelCaseToUnderscoreTest.php';
require_once 'Zend/Filter/Word/SeparatorToCamelCaseTest.php';
require_once 'Zend/Filter/Word/DashToCamelCaseTest.php';
require_once 'Zend/Filter/Word/UnderscoreToCamelCaseTest.php';
require_once 'Zend/Filter/Word/DashToUnderscoreTest.php';
require_once 'Zend/Filter/Word/UnderscoreToDashTest.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter_AllTests
{
    /**
     * Runs this test suite
     *
     * @return void
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Creates and returns this test suite
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_Filter');

        $suite->addTestSuite('Zend_Filter_AlnumTest');
        $suite->addTestSuite('Zend_Filter_AlphaTest');
        $suite->addTestSuite('Zend_Filter_BaseNameTest');
        $suite->addTestSuite('Zend_Filter_DigitsTest');
        $suite->addTestSuite('Zend_Filter_DirTest');
        $suite->addTestSuite('Zend_Filter_HtmlEntitiesTest');
        $suite->addTestSuite('Zend_Filter_InflectorTest');
        $suite->addTestSuite('Zend_Filter_IntTest');
        $suite->addTestSuite('Zend_Filter_PregReplaceTest');
        $suite->addTestSuite('Zend_Filter_RealPathTest');
        $suite->addTestSuite('Zend_Filter_StringToLowerTest');
        $suite->addTestSuite('Zend_Filter_StringToUpperTest');
        $suite->addTestSuite('Zend_Filter_StringTrimTest');
        $suite->addTestSuite('Zend_Filter_StripNewlinesTest');
        $suite->addTestSuite('Zend_Filter_StripTagsTest');
        $suite->addTestSuite('Zend_Filter_InputTest');
        $suite->addTestSuite('Zend_Filter_Word_CamelCaseToDashTest');
        $suite->addTestSuite('Zend_Filter_Word_CamelCaseToSeparatorTest');
        $suite->addTestSuite('Zend_Filter_Word_CamelCaseToUnderscoreTest');
        $suite->addTestSuite('Zend_Filter_Word_SeparatorToCamelCaseTest');
        $suite->addTestSuite('Zend_Filter_Word_DashToCamelCaseTest');
        $suite->addTestSuite('Zend_Filter_Word_UnderscoreToCamelCaseTest');
        $suite->addTestSuite('Zend_Filter_Word_DashToUnderscoreTest');
        $suite->addTestSuite('Zend_Filter_Word_UnderscoreToDashTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Filter_AllTests::main') {
    Zend_Filter_AllTests::main();
}
