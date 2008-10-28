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
 * @package    Zend_Memory
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AccessControllerTest.php 8468 2008-02-29 17:56:34Z darby $
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/** Zend_Memory */
require_once 'Zend/Memory.php';

/**
 * @category   Zend
 * @package    Zend_Memory
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Memory_Container_AccessControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Memory manager, used for tests
     *
     * @var Zend_Memory_Manager
     */
    private $_memoryManager = null;

    /**
     * Retrieve memory manager
     *
     */
    private function _getMemoryManager()
    {
        if ($this->_memoryManager === null) {
            $backendOptions = array('cache_dir' => dirname(__FILE__) . '/_files/'); // Directory where to put the cache files
            $this->_memoryManager = Zend_Memory::factory('File', $backendOptions);
        }

        return $this->_memoryManager;
    }



    /**
     * tests the Movable memory container object creation
     */
    public function testCreation()
    {
        $memoryManager  = $this->_getMemoryManager();
        $memObject      = $memoryManager->create('012345678');

        $this->assertTrue($memObject instanceof Zend_Memory_AccessController);
    }


    /**
     * tests the value access methods
     */
    public function testValueAccess()
    {
        $memoryManager  = $this->_getMemoryManager();
        $memObject      = $memoryManager->create('0123456789');

        // getRef() method
        $this->assertEquals($memObject->getRef(), '0123456789');

        $valueRef = &$memObject->getRef();
        $valueRef[3] = '_';
        $this->assertEquals($memObject->getRef(), '012_456789');

        if (version_compare(PHP_VERSION, '5.2') < 0) {
            // Skip next tests for PHP versions before 5.2
            return;
        }

        // value property
        $this->assertEquals((string)$memObject->value, '012_456789');

        $memObject->value[7] = '_';
        $this->assertEquals((string)$memObject->value, '012_456_89');

        $memObject->value = 'another value';
        $this->assertTrue($memObject->value instanceof Zend_Memory_Value);
        $this->assertEquals((string)$memObject->value, 'another value');
    }


    /**
     * tests lock()/unlock()/isLocked() functions
     */
    public function testLock()
    {
        $memoryManager  = $this->_getMemoryManager();
        $memObject      = $memoryManager->create('012345678');

        $this->assertFalse((boolean)$memObject->isLocked());

        $memObject->lock();
        $this->assertTrue((boolean)$memObject->isLocked());

        $memObject->unlock();
        $this->assertFalse((boolean)$memObject->isLocked());
    }
}
