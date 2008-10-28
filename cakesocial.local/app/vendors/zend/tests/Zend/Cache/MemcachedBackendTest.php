<?php
/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
 
 /**
 * Zend_Cache
 */
require_once 'Zend/Cache.php';
require_once 'Zend/Cache/Backend/Memcached.php';

/**
 * Common tests for backends
 */
require_once 'CommonBackendTest.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @package    Zend_Cache
 * @subpackage UnitTests
 */
class Zend_Cache_MemcachedBackendTest extends Zend_Cache_CommonBackendTest {
    
    protected $_instance;
 
    public function __construct()
    {
        parent::__construct('Zend_Cache_Backend_Memcached');
    }
       
    public function setUp($notag = true)
    {        
        $server = array(
            'host' => TESTS_ZEND_CACHE_MEMCACHED_HOST,
            'port' => TESTS_ZEND_CACHE_MEMCACHED_PORT,
            'persistent' => TESTS_ZEND_CACHE_MEMCACHED_PERSISTENT
        );
        $options = array(
            'servers' => array(0 => $server)
        );
        $this->_instance = new Zend_Cache_Backend_Memcached($options);
        parent::setUp($notag);    
    }
    
    public function tearDown()
    {
        parent::tearDown();
        unset($this->_instance);
        // We have to wait after a memcache flush
        sleep(1);
    }
    
    public function testConstructorCorrectCall()
    {
        $test = new Zend_Cache_Backend_Memcached(); 
    }
    
    public function testCleanModeOld() 
    {
        $this->_instance->setDirectives(array('logging' => false));
        $this->_instance->clean('old');
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testCleanModeMatchingTags() 
    {
        $this->_instance->setDirectives(array('logging' => false));
        $this->_instance->clean('matchingTag', array('tag1'));
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testCleanModeNotMatchingTags() 
    {
        $this->_instance->setDirectives(array('logging' => false));        
        $this->_instance->clean('notMatchingTag', array('tag1'));
        // do nothing, just to see if an error occured
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testGetWithCompression() 
    {
        $this->_instance->setOption('compression', true);
        $this->testGetWithAnExistingCacheIdAndUTFCharacters();
    }
    
    public function testConstructorWithAnAlternativeSyntax()
    {
        $server = array(
            'host' => TESTS_ZEND_CACHE_MEMCACHED_HOST,
            'port' => TESTS_ZEND_CACHE_MEMCACHED_PORT,
            'persistent' => TESTS_ZEND_CACHE_MEMCACHED_PERSISTENT
        );
        $options = array(
            'servers' => $server
        );
        $this->_instance = new Zend_Cache_Backend_Memcached($options);
        $this->testGetWithAnExistingCacheIdAndUTFCharacters();
    }
    
    // Because of limitations of this backend...
    public function testGetWithAnExpiredCacheId() {}
    public function testCleanModeMatchingTags2() {}
    public function testCleanModeNotMatchingTags2() {}
    public function testCleanModeNotMatchingTags3() {}
    public function testSaveCorrectCall()
    {
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveCorrectCall();
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testSaveWithNullLifeTime()
    {
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveWithNullLifeTime();
        $this->_instance->setDirectives(array('logging' => true));
    }
    
    public function testSaveWithSpecificLifeTime() 
    {
        
        $this->_instance->setDirectives(array('logging' => false));
        parent::testSaveWithSpecificLifeTime();
        $this->_instance->setDirectives(array('logging' => true));
    }
}


