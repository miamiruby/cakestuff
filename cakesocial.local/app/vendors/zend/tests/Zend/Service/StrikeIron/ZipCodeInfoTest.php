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
 * @package    Zend_Service
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ZipCodeInfoTest.php 8064 2008-02-16 10:58:39Z thomas $
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @see Zend_Service_StrikeIron
 */
require_once 'Zend/Service/StrikeIron.php';

/**
 * @see Zend_Service_StrikeIron_ZipCodeInfo
 */
require_once 'Zend/Service/StrikeIron/ZipCodeInfo.php';


/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_StrikeIron_ZipCodeInfoTest extends PHPUnit_Framework_TestCase 
{
    public function setUp()
    {
        $this->soapClient = new stdclass();
        $this->service = new Zend_Service_StrikeIron_ZipCodeInfo(array('client' => $this->soapClient));
    }
    
    public function testInheritsFromBase()
    {
        $this->assertType('Zend_Service_StrikeIron_Base', $this->service);
    }
    
    public function testHasCorrectWsdl()
    {
        $wsdl = 'http://sdpws.strikeiron.com/zf1.StrikeIron/sdpZIPCodeInfo?WSDL';
        $this->assertEquals($wsdl, $this->service->getWsdl());
    }

    public function testInstantiationFromFactory()
    {
        $strikeIron = new Zend_Service_StrikeIron(array('client' => $this->soapClient));
        $client = $strikeIron->getService(array('class' => 'ZipCodeInfo'));
        
        $this->assertType('Zend_Service_StrikeIron_ZipCodeInfo', $client);
    }

}