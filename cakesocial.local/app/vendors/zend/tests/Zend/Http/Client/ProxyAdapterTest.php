<?php

// Read local configuration
if (! defined('TESTS_ZEND_HTTP_CLIENT_BASEURI') &&
    is_readable('TestConfiguration.php')) {

    require_once 'TestConfiguration.php';
}

require_once dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

require_once 'Zend/Http/Client.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'SocketTest.php';

/**
 * Zend_Http_Client_Adapter_Proxy test suite.
 *
 * In order to run, TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY must point to a working
 * proxy server, which can access TESTS_ZEND_HTTP_CLIENT_BASEURI.
 *
 * See TestConfiguration.php.dist for more information.
 *
 * @category   Zend
 * @package    Zend_Http_Client
 * @subpackage UnitTests
 * @version    $Id: ProxyAdapterTest.php 11915 2008-10-12 18:29:09Z alexander $
 * @copyright
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Http_Client_ProxyAdapterTest extends Zend_Http_Client_SocketTest
{
	/**
	 * Configuration array
	 *
	 * @var array
	 */
	protected function setUp()
	{
		if (defined('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY') &&
		      TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY) {

		    list($host, $port) = split(':', TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY, 2);

		    if (! $host)
		        $this->markTestSkipped("No valid proxy host name or address specified.");

		    $port = (int) $port;
		    if ($port == 0) {
		    	$port = 8080;
		    } else {
			    if (($port < 1 || $port > 65535))
		    		$this->markTestSkipped("$port is not a valid proxy port number. Should be between 1 and 65535.");
		    }

		    $user = '';
		    $pass = '';
		    if (defined('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_USER') &&
		        TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_USER)
		            $user = TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_USER;

		    if (defined('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_PASS') &&
		        TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_PASS)
		            $pass = TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_PASS;


		    $this->config = array(
		        'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
		        'proxy_host' => $host,
		        'proxy_port' => $port,
		        'proxy_user' => $user,
		        'proxy_pass' => $pass,
		    );

		    parent::setUp();

		} else {
                        $this->markTestSkipped("Zend_Http_Client proxy server tests are not enabled in TestConfiguration.php");
		}
	}

	public function testGetLastRequest()
	{
		// Overriding, this one will not work and is not required for the
		// proxy test
	}
}
