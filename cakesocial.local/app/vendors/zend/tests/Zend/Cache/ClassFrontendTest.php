<?php
/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
 
 /**
 * Zend_Cache
 */
require_once 'Zend/Cache.php';
require_once 'Zend/Cache/Frontend/Class.php';
require_once 'Zend/Cache/Backend/Test.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

class test {

    private $_string = 'hello !';
    
    public static function foobar($param1, $param2) {
        echo "foobar_output($param1, $param2)";
        return "foobar_return($param1, $param2)";   
    }
    
    public function foobar2($param1, $param2) {
        echo($this->_string);
        echo "foobar2_output($param1, $param2)";
        return "foobar2_return($param1, $param2)";   
    }

}

/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
class Zend_Cache_ClassFrontendTest extends PHPUnit_Framework_TestCase {
    
    private $_instance1;
    private $_instance2;
    
    public function setUp()
    {
        if (!$this->_instance1) {
            $options1 = array(
                'cached_entity' => 'test'
            );
            $this->_instance1 = new Zend_Cache_Frontend_Class($options1);
            $this->_backend1 = new Zend_Cache_Backend_Test();
            $this->_instance1->setBackend($this->_backend1);
        }
        if (!$this->_instance2) {
            $options2 = array(
                'cached_entity' => new test()
            );
            $this->_instance2 = new Zend_Cache_Frontend_Class($options2);
            $this->_backend2 = new Zend_Cache_Backend_Test();
            $this->_instance2->setBackend($this->_backend2);
        }  
    }
    
    public function tearDown()
    {
        unset($this->_instance1);
        unset($this->_instance2);
    }
    
    public function testConstructorCorrectCall1()
    {
        $options = array(
            'cache_by_default' => false,
            'cached_entity' => 'test'
        );
        $test = new Zend_Cache_Frontend_Class($options);      
    }
    
    public function testConstructorCorrectCall2()
    {
        $options = array(
            'cache_by_default' => false,
            'cached_entity' => new test()
        );
        $test = new Zend_Cache_Frontend_Class($options);      
    }
        
    public function testConstructorBadCall()
    {
        $options = array(
            'cache_by_default' => true
        );
        try {
            $test = new Zend_Cache_Frontend_Class($options);      
        } catch (Zend_Cache_Exception $e) {
            return;
        }
        $this->fail('Zend_Cache_Exception was expected but not thrown'); 
    }
    
    public function testCallCorrectCall1()
    {
        ob_start();
        ob_implicit_flush(false);        
        $return = $this->_instance1->foobar('param1', 'param2');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('bar', $return);
        $this->assertEquals('foo', $data);
    }
    
    public function testCallCorrectCall2()
    {
        ob_start();
        ob_implicit_flush(false);        
        $return = $this->_instance1->foobar('param3', 'param4');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('foobar_return(param3, param4)', $return);
        $this->assertEquals('foobar_output(param3, param4)', $data);
    }
    
    public function testCallCorrectCall3()
    {
        ob_start();
        ob_implicit_flush(false);        
        $return = $this->_instance2->foobar2('param1', 'param2');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('bar', $return);
        $this->assertEquals('foo', $data);
    }
    
    public function testCallCorrectCall4()
    {
        ob_start();
        ob_implicit_flush(false);        
        $return = $this->_instance2->foobar2('param3', 'param4');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('foobar2_return(param3, param4)', $return);
        $this->assertEquals('hello !foobar2_output(param3, param4)', $data);
    }
    
    public function testCallCorrectCall5()
    {
        // cacheByDefault = false
        $this->_instance1->setOption('cache_by_default', false);
        ob_start();
        ob_implicit_flush(false);
        $return = $this->_instance1->foobar('param1', 'param2');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('foobar_return(param1, param2)', $return);
        $this->assertEquals('foobar_output(param1, param2)', $data);
    }
    
    public function testCallCorrectCall6()
    {
        // cacheByDefault = false
        // cachedMethods = array('foobar')
        $this->_instance1->setOption('cache_by_default', false);
        $this->_instance1->setOption('cached_methods', array('foobar'));
        ob_start();
        ob_implicit_flush(false);
        $return = $this->_instance1->foobar('param1', 'param2');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('bar', $return);
        $this->assertEquals('foo', $data);
    }    
    
    public function testCallCorrectCall7()
    {
        // cacheByDefault = true
        // nonCachedMethods = array('foobar')
        $this->_instance1->setOption('cache_by_default', true);
        $this->_instance1->setOption('non_cached_methods', array('foobar'));
        ob_start();
        ob_implicit_flush(false);
        $return = $this->_instance1->foobar('param1', 'param2');
        $data = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
        $this->assertEquals('foobar_return(param1, param2)', $return);
        $this->assertEquals('foobar_output(param1, param2)', $data);
    }
    
    public function testConstructorWithABadCachedEntity()
    {
        try {
            $options = array(
                'cached_entity' => array()
            );
            $instance = new Zend_Cache_Frontend_Class($options);           
        } catch (Zend_Cache_Exception $e) {
            return;
        }
        $this->fail('Zend_Cache_Exception was expected but not thrown'); 
    }   
}

