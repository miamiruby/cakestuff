<?php

/**
 * @category   Zend
 * @package    Zend_Translate
 * @subpackage UnitTests
 */


/**
 * Zend_Translate_Adapter_Array
 */
require_once 'Zend/Translate/Adapter/Array.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @subpackage UnitTests
 */
class Zend_Translate_ArrayTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ));
        $this->assertTrue($adapter instanceof Zend_Translate_Adapter_Array);

        try {
            $adapter = new Zend_Translate_Adapter_Array('hastofail');
            $this->fail();
        } catch (Zend_Translate_Exception $e) {
            // success
        }

        $adapter = new Zend_Translate_Adapter_Array(dirname(__FILE__) . '/_files/array.php');
        $this->assertTrue($adapter instanceof Zend_Translate_Adapter_Array);

        try {
            $adapter = new Zend_Translate_Adapter_Array(dirname(__FILE__) . '/_files/translation_en.csv');
            $this->fail();
        } catch (Zend_Translate_Exception $e) {
            // success
        }
    }

    public function testToString()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ));
        $this->assertEquals('Array', $adapter->toString());
    }

    public function testTranslate()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ));
        $this->assertEquals('Message 1 (en)', $adapter->translate('msg1'));
        $this->assertEquals('msg4',           $adapter->translate('msg4'));
    }

    public function testIsTranslated()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ));
        $this->assertTrue( $adapter->isTranslated('msg1'));
        $this->assertFalse($adapter->isTranslated('msg4'));
    }

    public function testLoadTranslationData()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ),
                                                    'en');
        $this->assertEquals('Message 1 (en)', $adapter->translate('msg1'));
        $this->assertEquals('Message 1 (en)', $adapter->_('msg1'        ));
        $this->assertEquals('msg4',           $adapter->translate('msg4'));

        $adapter->addTranslation(array('msg4' => 'Message 4 (en)',
                                       'msg5' => 'Message 5 (en)',
                                       'msg6' => 'Message 6 (en)'
                                      ),'en');
        $this->assertEquals('Message 5 (en)', $adapter->translate('msg5'));

        $adapter->addTranslation(array('msg1' => 'Message 1 (ru)',
                                       'msg2' => 'Message 2 (ru)',
                                       'msg3' => 'Message 3 (ru)'
                                      ), 'ru');
        $this->assertEquals('Message 1 (ru)', $adapter->translate('msg1', 'ru'));

        $adapter->addTranslation(array('msg4' => 'Message 4 (ru)',
                                       'msg5' => 'Message 5 (ru)',
                                       'msg6' => 'Message 6 (ru)'
                                      ), 'ru',
                                 array('clear' => true));
        $this->assertEquals('msg2',           $adapter->translate('msg2', 'ru'));
        $this->assertEquals('Message 4 (ru)', $adapter->translate('msg4', 'ru'));
        $this->assertEquals('msg1',           $adapter->translate('msg1', 'xx'));
        $this->assertEquals('Message 4 (ru)', $adapter->translate('msg4', 'ru_RU'));

        try {
            $adapter->addTranslation(array('msg1' => 'Message 1 (ru)',
                                           'msg2' => 'Message 2 (ru)',
                                           'msg3' => 'Message 3 (ru)'
                                          ), 'xx');
            $this->fail("exception expected");
        } catch (Zend_Translate_Exception $e) {
            // success
        }
    }

    public function testOptions()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ), 'en');
        $adapter->setOptions(array('testoption' => 'testkey'));
        $this->assertEquals(array('testoption' => 'testkey', 'clear' => false, 'scan' => null, 'locale' => 'en'), $adapter->getOptions());
        $this->assertEquals('testkey', $adapter->getOptions('testoption'));
        $this->assertTrue(is_null($adapter->getOptions('nooption')));
    }

    public function testLocale()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ), 'en');
        $this->assertEquals('en', $adapter->getLocale());
        $locale = new Zend_Locale('en');
        $adapter->setLocale($locale);
        $this->assertEquals('en', $adapter->getLocale());

        try {
            $adapter->setLocale('nolocale');
            $this->fail("exception expected");
        } catch (Zend_Translate_Exception $e) {
            // success
        }
        try {
            $adapter->setLocale('de');
            $this->fail("exception expected");
        } catch (Zend_Translate_Exception $e) {
            // success
        }
    }

    public function testList()
    {
        $adapter = new Zend_Translate_Adapter_Array(array('msg1' => 'Message 1 (en)',
                                                          'msg2' => 'Message 2 (en)',
                                                          'msg3' => 'Message 3 (en)'
                                                         ), 'en');
        $this->assertEquals(array('en' => 'en'), $adapter->getList());
        $adapter->addTranslation(array('msg1'), 'de');
        $this->assertEquals(array('en' => 'en', 'de' => 'de'), $adapter->getList());
        $this->assertTrue($adapter->isAvailable('de'));
        $locale = new Zend_Locale('en');
        $this->assertTrue( $adapter->isAvailable($locale));
        $this->assertFalse($adapter->isAvailable('sr'   ));
    }
}
