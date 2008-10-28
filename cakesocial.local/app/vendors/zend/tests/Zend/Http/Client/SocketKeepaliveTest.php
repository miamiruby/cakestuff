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
 * This Testsuite includes all Zend_Http_Client that require a working web
 * server to perform. It was designed to be extendable, so that several
 * test suites could be run against several servers, with different client
 * adapters and configurations.
 *
 * Note that $this->baseuri must point to a directory on a web server
 * containing all the files under the _files directory. You should symlink
 * or copy these files and set 'baseuri' properly.
 *
 * You can also set the proper constand in your test configuration file to
 * point to the right place.
 *
 * @category   Zend
 * @package    Zend_Http_Client
 * @subpackage UnitTests
 * @version    $Id: SocketKeepaliveTest.php 11915 2008-10-12 18:29:09Z alexander $
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Http_Client_SocketKeepaliveTest extends Zend_Http_Client_SocketTest
{
	/**
	 * Configuration array
	 *
	 * @var array
	 */
	protected $config = array(
		'adapter'     => 'Zend_Http_Client_Adapter_Socket',
		'keepalive'   => true
	);
}
