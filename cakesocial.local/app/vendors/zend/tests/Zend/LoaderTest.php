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
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: LoaderTest.php 9301 2008-04-24 13:21:59Z doctorrock83 $
 */

// Call Zend_LoaderTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_LoaderTest::main');
}

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @category   Zend
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_LoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Zend_LoaderTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Tests that a class can be loaded from a well-formed PHP file
     */
    public function testLoaderClassValid()
    {
        $dir = implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR);

        Zend_Loader::loadClass('Class1', $dir);
    }

    public function testLoaderInterfaceViaLoadClass()
    {
        try {
            Zend_Loader::loadClass('Zend_Controller_Dispatcher_Interface');
        } catch (Zend_Exception $e) {
            $this->fail('Loading interfaces should not fail');
        }
    }

    public function testLoaderLoadClassWithDotDir()
    {
        $dirs = array('.');
        try {
            Zend_Loader::loadClass('Zend_Version', $dirs);
        } catch (Zend_Exception $e) {
            $this->fail('Loading from dot should not fail');
        }
    }

    /**
     * Tests that an exception is thrown when a file is loaded but the
     * class is not found within the file
     */
    public function testLoaderClassNonexistent()
    {
        $dir = implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR);

        try {
            Zend_Loader::loadClass('ClassNonexistent', $dir);
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertRegExp('/file(.*)does not exist or class(.*)not found/i', $e->getMessage());
        }
    }

    /**
     * Tests that an exception is thrown if the $dirs argument is
     * not a string or an array.
     */
    public function testLoaderInvalidDirs()
    {
        try {
            Zend_Loader::loadClass('Zend_Invalid_Dirs', new stdClass());
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertEquals('Directory argument must be a string or an array', $e->getMessage());
        }
    }

    /**
     * Tests that a class can be loaded from the search directories.
     */
    public function testLoaderClassSearchDirs()
    {
        $dirs = array();
        foreach (array('_testDir1', '_testDir2') as $dir) {
            $dirs[] = implode(array(dirname(__FILE__), '_files', $dir), DIRECTORY_SEPARATOR);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1', $dirs);
        Zend_Loader::loadClass('Class2', $dirs);
    }

    /**
     * Tests that a class locatedin a subdirectory can be loaded from the search directories
     */
    public function testLoaderClassSearchSubDirs()
    {
        $dirs = array();
        foreach (array('_testDir1', '_testDir2') as $dir) {
            $dirs[] = implode(array(dirname(__FILE__), '_files', $dir), DIRECTORY_SEPARATOR);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1_Subclass2', $dirs);
    }

    /**
     * Tests that the security filter catches illegal characters.
     */
    public function testLoaderClassIllegalFilename()
    {
        try {
            Zend_Loader::loadClass('/path/:to/@danger');
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertRegExp('/security(.*)filename/i', $e->getMessage());
        }
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is null
     */
    public function testLoaderFileIncludePathEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(array($saveIncludePath, implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR)), PATH_SEPARATOR));

        $this->assertTrue(Zend_Loader::loadFile('Class3.php', null));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is non-null
     * This was not working vis-a-vis ZF-1174
     */
    public function testLoaderFileIncludePathNonEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(array($saveIncludePath, implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR)), PATH_SEPARATOR));

        $this->assertTrue(Zend_Loader::loadFile('Class4.php', implode(PATH_SEPARATOR, array('foo', 'bar'))));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that isReadable works
     */
    public function testLoaderIsReadable()
    {
        $this->assertTrue(Zend_Loader::isReadable(__FILE__));
        $this->assertFalse(Zend_Loader::isReadable(__FILE__ . '.foobaar'));
        
        // test that a file in include_path gets loaded, see ZF-2985
        $this->assertTrue(Zend_Loader::isReadable('Zend/Controller/Front.php'));
    }

    /**
     * Tests that autoload works for valid classes and interfaces
     */
    public function testLoaderAutoloadLoadsValidClasses()
    {
        $this->assertEquals('Zend_Db_Profiler_Exception', Zend_Loader::autoload('Zend_Db_Profiler_Exception'));
        $this->assertEquals('Zend_Auth_Storage_Interface', Zend_Loader::autoload('Zend_Auth_Storage_Interface'));
    }

    /**
     * Tests that autoload returns false on invalid classes
     */
    public function testLoaderAutoloadFailsOnInvalidClasses()
    {
        $this->assertFalse(Zend_Loader::autoload('Zend_FooBar_Magic_Abstract'));
    }

    public function testLoaderRegisterAutoloadRegisters()
    {
        if (!function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload not installed on this PHP installation");
        }

        Zend_Loader::registerAutoload();
        $autoloaders = spl_autoload_functions();
        $expected    = array('Zend_Loader', 'autoload');
        $found       = false;
        foreach($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Failed to register Zend_Loader::autoload() with spl_autoload");

        spl_autoload_unregister($expected);
    }

    public function testLoaderRegisterAutoloadExtendedClassNeedsAutoloadMethod()
    {
        if (!function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload not installed on this PHP installation");
        }

        Zend_Loader::registerAutoload('Zend_Loader_MyLoader');
        $autoloaders = spl_autoload_functions();
        $expected    = array('Zend_Loader_MyLoader', 'autoload');
        $found       = false;
        foreach ($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, "Failed to register Zend_Loader_MyLoader::autoload() with spl_autoload");

        spl_autoload_unregister($expected);
    }

    public function testLoaderRegisterAutoloadExtendedClassWithAutoloadMethod()
    {
        if (!function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload not installed on this PHP installation");
        }

        Zend_Loader::registerAutoload('Zend_Loader_MyOverloader');
        $autoloaders = spl_autoload_functions();
        $expected    = array('Zend_Loader_MyOverloader', 'autoload');
        $found       = false;
        foreach ($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Failed to register Zend_Loader_MyOverloader::autoload() with spl_autoload");

        // try to instantiate a class that is known not to be loaded
        $obj = new Zend_Loader_AutoloadableClass();

        // now it should be loaded
        $this->assertTrue(class_exists('Zend_Loader_AutoloadableClass'),
            'Expected Zend_Loader_AutoloadableClass to be loaded');

        // and we verify it is the correct type
        $this->assertType('Zend_Loader_AutoloadableClass', $obj,
            'Expected to instantiate Zend_Loader_AutoloadableClass, got '.get_class($obj));

        spl_autoload_unregister($expected);
    }

    public function testLoaderRegisterAutoloadFailsWithoutSplAutoload()
    {
        if (function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload() is installed on this PHP installation; cannot test for failure");
        }

        try {
            Zend_Loader::registerAutoload();
            $this->fail('registerAutoload should fail without spl_autoload');
        } catch (Zend_Exception $e) {
        }
    }

    public function testLoaderRegisterAutoloadInvalidClass()
    {
        if (!function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload() not installed on this PHP installation");
        }

        try {
            Zend_Loader::registerAutoload('stdClass');
            $this->fail('registerAutoload should fail without spl_autoload');
        } catch (Zend_Exception $e) {
            $this->assertEquals('The class "stdClass" does not have an autoload() method', $e->getMessage());
        }
    }

    public function testLoaderUnregisterAutoload()
    {
        if (!function_exists('spl_autoload_register')) {
            $this->markTestSkipped("spl_autoload() not installed on this PHP installation");
        }

        Zend_Loader::registerAutoload();
        $autoloaders = spl_autoload_functions();
        $expected    = array('Zend_Loader', 'autoload');
        $found       = false;
        foreach($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Failed to register Zend_Loader::autoload() with spl_autoload");

        Zend_Loader::registerAutoload('Zend_Loader', false);
        $autoloaders = spl_autoload_functions();
        $expected    = array('Zend_Loader', 'autoload');
        $found       = false;
        foreach($autoloaders as $function) {
            if ($expected == $function) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, "Failed to unregister Zend_Loader::autoload() with spl_autoload");
    }

    /**
     * @return void
     * @see    http://framework.zend.com/issues/browse/ZF-2463
     */
    public function testLoaderAutoloadDoesNotHideParseError()
    {
        $command = 'php -d include_path='
            . escapeshellarg(get_include_path())
            . ' Zend/Loader/AutoloadDoesNotHideParseError.php 2>&1';
        $output = shell_exec($command);
        $this->assertRegexp('/error, unexpected T_STRING, expecting T_FUNCTION/i', $output, $output);
    }
}

// Call Zend_LoaderTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD === 'Zend_LoaderTest::main') {
    Zend_LoaderTest::main();
}
