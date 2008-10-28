<?php
/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
 
 /**
 * Zend_Cache
 */
require_once 'Zend/Cache.php';
require_once 'Zend/Cache/Frontend/File.php';
require_once 'Zend/Cache/Backend/Test.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
class Zend_Cache_FileFrontendTest extends PHPUnit_Framework_TestCase {
    
    private $_instance1;
    private $_instance2;
    
    
    public function setUp()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->_masterFile = $this->_getTmpDirWindows() . DIRECTORY_SEPARATOR . 'zend_cache_master';
        } else {
            $this->_masterFile = $this->_getTmpDirUnix() . DIRECTORY_SEPARATOR . 'zend_cache_master';
        }
        if (!$this->_instance1) {
            touch($this->_masterFile, 123455);
            $this->_instance1 = new Zend_Cache_Frontend_File(array('master_file' => $this->_masterFile));           
            $this->_backend = new Zend_Cache_Backend_Test();
            $this->_instance1->setBackend($this->_backend);
        }
        if (!$this->_instance2) {
            touch($this->_masterFile);
            $this->_instance2 = new Zend_Cache_Frontend_File(array('master_file' => $this->_masterFile));           
            $this->_backend = new Zend_Cache_Backend_Test();
            $this->_instance2->setBackend($this->_backend);
        }
        
    }
    
    public function tearDown()
    {
        unset($this->_instance1);
        unlink($this->_masterFile);
    }
    
    private function _getTmpDirWindows()
    {
        if (isset($_ENV['TEMP'])) {
            return $_ENV['TEMP'];
        }
        if (isset($_ENV['TMP'])) {
            return $_ENV['TMP'];
        }
        if (isset($_ENV['windir'])) {
            return $_ENV['windir'] . '\\temp';
        }
        if (isset($_ENV['SystemRoot'])) {
            return $_ENV['SystemRoot'] . '\\temp';
        }
        if (isset($_SERVER['TEMP'])) {
            return $_SERVER['TEMP'];
        }
        if (isset($_SERVER['TMP'])) {
            return $_SERVER['TMP'];
        }
        if (isset($_SERVER['windir'])) {
            return $_SERVER['windir'] . '\\temp';
        }
        if (isset($_SERVER['SystemRoot'])) {
            return $_SERVER['SystemRoot'] . '\\temp';
        }
        return '\temp';
    }
    
    private function _getTmpDirUnix()
    {
        if (isset($_ENV['TMPDIR'])) {
            return $_ENV['TMPDIR'];
        }
        if (isset($_SERVER['TMPDIR'])) {
            return $_SERVER['TMPDIR'];
        }
        return '/tmp';
    }
    
    public function testConstructorCorrectCall()
    {
        $test = new Zend_Cache_Frontend_File(array('master_file' => $this->_masterFile, 'lifetime' => 3600, 'caching' => true));      
    }
    
    public function testConstructorBadCall1()
    {
        # no masterfile
        try {
            $test = new Zend_Cache_Frontend_File(array('lifetime' => 3600, 'caching' => true));      
        } catch (Zend_Cache_Exception $e) {
            return;
        }
        $this->fail('Zend_Cache_Exception was expected but not thrown'); 
    }
    
    public function testConstructorBadCall2()
    {
        # incorrect option
        try {
            $test = new Zend_Cache_Frontend_File(array('master_file' => $this->_masterFile, 'foo' => 3600));      
        } catch (Zend_Cache_Exception $e) {
            return;
        }
        $this->fail('Zend_Cache_Exception was expected but not thrown'); 
    }
    
    public function testTestCorrectCall1()
    {
        $this->assertFalse($this->_instance1->test('false'));
    }
    
    public function testTestCorrectCall2()
    {
        $this->assertTrue($this->_instance1->test('cache_id') > 1);
    }
    
    public function testTestCorrectCall3()
    {
        $this->assertFalse($this->_instance2->test('cache_id'));
    }
    
    public function testGetCorrectCall1()
    {
        $this->assertFalse($this->_instance1->load('false'));    
    }
    
    public function testGetCorrectCall2()
    {
        $this->assertEquals('foo', $this->_instance1->load('cache_id'));    
    }
    
    public function testGetCorrectCall3()
    {
        $this->assertFalse($this->_instance2->load('cache_id'));    
    }   
    
    public function testConstructorWithABadMasterFile()
    {
        try {
            $instance = new Zend_Cache_Frontend_File(array('master_file' => '/foo/bar/ljhfdjh/qhskldhqjk'));
        } catch (Zend_Cache_Exception $e) {
            return;
        }
        $this->fail('Zend_Cache_Exception was expected but not thrown'); 
    } 
    
    public function testGetWithDoNotTestCacheValidity()
    {
        $this->assertEquals('foo', $this->_instance1->load('cache_id', true));    
    }
    
}


