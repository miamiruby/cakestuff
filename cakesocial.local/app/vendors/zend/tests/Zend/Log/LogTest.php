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
 * @package    Zend_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: LogTest.php 8064 2008-02-16 10:58:39Z thomas $
 */

/** PHPUnit_Framework_TestCase */
require_once 'PHPUnit/Framework/TestCase.php';

/** Zend_Log */
require_once 'Zend/Log.php';

/** Zend_Log_Writer_Mock */
require_once 'Zend/Log/Writer/Mock.php';

/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: LogTest.php 8064 2008-02-16 10:58:39Z thomas $
 */
class Zend_Log_LogTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->log = fopen('php://memory', 'a');
        $this->writer = new Zend_Log_Writer_Stream($this->log);
    }
    
    // Writers
    
    public function testWriterCanBeAddedWithConstructor()
    {
        $logger = new Zend_Log($this->writer);
        $logger->log($message = 'message-to-long', Zend_Log::INFO);

        rewind($this->log);
        $this->assertContains($message, stream_get_contents($this->log));
    }

    public function testAddWriter()
    {
        $logger = new Zend_Log();
        $logger->addWriter($this->writer);
        $logger->log($message = 'message-to-log', Zend_Log::INFO);
    
        rewind($this->log);
        $this->assertContains($message, stream_get_contents($this->log));
    }

    public function testAddWriterAddsMultipleWriters()
    {
        $logger = new Zend_Log();

        // create writers for two separate streams of temporary memory
        $log1    = fopen('php://memory', 'a');
        $writer1 = new Zend_Log_Writer_Stream($log1);
        $log2    = fopen('php://memory', 'a');
        $writer2 = new Zend_Log_Writer_Stream($log2);

        // add the writers
        $logger->addWriter($writer1);
        $logger->addWriter($writer2);

        // log to both writers
        $logger->log($message = 'message-sent-to-both-logs', Zend_Log::INFO);
        
        // verify both writers were called by the logger
        rewind($log1);
        $this->assertContains($message, stream_get_contents($log1));
        rewind($log2);
        $this->assertContains($message, stream_get_contents($log2));
        
        // prove the two memory streams are different 
        // and both writers were indeed called
        fwrite($log1, 'foo');
        $this->assertNotEquals(ftell($log1), ftell($log2));
    }

    public function testLoggerThrowsWhenNoWriters()
    {
        $logger = new Zend_Log();
        try {
            $logger->log('message', Zend_Log::INFO);
            $this->fail();
        } catch (Zend_Log_Exception $e) {
            $this->assertRegexp('/no writer/i', $e->getMessage());
        }
    }

    public function testDestructorCallsShutdownOnEachWriter()
    {
        $writer1 = new Zend_Log_Writer_Mock();
        $writer2 = new Zend_Log_Writer_Mock();

        $logger = new Zend_Log();
        $logger->addWriter($writer1);
        $logger->addWriter($writer2);
        
        $this->assertFalse($writer1->shutdown);
        $this->assertFalse($writer2->shutdown);
        
        $logger = null;
        
        $this->assertTrue($writer1->shutdown);
        $this->assertTrue($writer2->shutdown);
    }

    // Priorities

    public function testLogThrowsOnBadLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        try {
            $logger->log('foo', 42);
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/bad log priority/i', $e->getMessage());
        }
    }

    public function testLogThrough__callThrowsOnBadLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        try {
            $logger->nonexistantPriority('');
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/bad log priority/i', $e->getMessage());
        }
    }

    public function testAddingPriorityThrowsWhenOverridingBuiltinLogPriority()
    {
        try {
            $logger = new Zend_Log($this->writer);
            $logger->addPriority('BOB', 0);
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Log_Exception', $e);
            $this->assertRegExp('/existing priorities/i', $e->getMessage());
        }
    
    }
    
    public function testAddLogPriority()
    {
        $logger = new Zend_Log($this->writer);
        $logger->addPriority('EIGHT', $priority = 8);

        $logger->eight($message = 'eight message');

        rewind($this->log);
        $logdata = stream_get_contents($this->log);
        $this->assertContains((string)$priority, $logdata);
        $this->assertContains($message, $logdata);
    }

    // Fields

    public function testLogWritesStandardFields() {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->info('foo');

        $this->assertEquals(1, count($mock->events));
        $event = array_shift($mock->events);

        $standardFields = array_flip(array('timestamp', 'priority', 'priorityName', 'message'));
        $this->assertEquals(array(), array_diff_key($event, $standardFields));
    }
    
    public function testLogWritesAndOverwritesExtraFields() {
        $logger = new Zend_Log($mock = new Zend_Log_Writer_Mock);
        $logger->setEventItem('foo', 42);
        $logger->setEventItem($field = 'bar', $value = 43);
        $logger->info('foo');

        $this->assertEquals(1, count($mock->events));
        $event = array_shift($mock->events);        

        $this->assertTrue(array_key_exists($field, $event));
        $this->assertEquals($value, $event[$field]);
    }
}
