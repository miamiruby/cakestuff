<?php
// Call Zend_Controller_Request_HttpTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Controller_Request_HttpTest::main");
}
require_once dirname(__FILE__) . '/../../../TestHelper.php';

require_once 'Zend/Controller/Request/Http.php';

class Zend_Controller_Request_HttpTest extends PHPUnit_Framework_TestCase 
{
    /**
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Original $_SERVER
     * @var array 
     */
    protected $_origServer;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Controller_Request_HttpTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        $this->_origServer = $_SERVER;
        $_GET  = array();
        $_POST = array();
        $this->_request = new Zend_Controller_Request_Http('http://framework.zend.com/news/3?var1=val1&var2=val2#anchor');
    }

    public function tearDown()
    {
        unset($this->_request);
        $_SERVER = $this->_origServer;
    }

    public function testSetGetControllerKey()
    {
        $this->_request->setControllerKey('controller');
        $this->assertEquals('controller', $this->_request->getControllerKey());

        $this->_request->setControllerKey('foo');
        $this->assertEquals('foo', $this->_request->getControllerKey());
    } 

    public function testSetGetActionKey()
    {
        $this->_request->setActionKey('action');
        $this->assertEquals('action', $this->_request->getActionKey());

        $this->_request->setActionKey('foo');
        $this->assertEquals('foo', $this->_request->getActionKey());
    } 

    public function testSetGetControllerName()
    {
        $this->_request->setControllerName('foo');
        $this->assertEquals('foo', $this->_request->getControllerName());

        $this->_request->setControllerName('bar');
        $this->assertEquals('bar', $this->_request->getControllerName());
    }
 
    public function testSetGetActionName()
    {
        $this->_request->setActionName('foo');
        $this->assertEquals('foo', $this->_request->getActionName());

        $this->_request->setActionName('bar');
        $this->assertEquals('bar', $this->_request->getActionName());
    }

    public function test__Get()
    {
        $_POST['baz']   = 'boo';
        $_COOKIE['bal'] = 'peen';
        $this->_request->setParam('foo', 'bar');

        foreach ($_ENV as $envKey => $expected) {
            if (isset($_ENV[$envKey]) && !empty($_ENV[$envKey])) {
                $expEnvKey = $envKey;
                break;
            }
        }

        $this->assertEquals('bar', $this->_request->foo);
        $this->assertEquals('val1', $this->_request->var1);
        $this->assertEquals('boo', $this->_request->baz);
        $this->assertEquals('peen', $this->_request->bal);
        $this->assertEquals($_SERVER['REQUEST_TIME'], $this->_request->REQUEST_TIME);
        $this->assertEquals($this->_request->getPathInfo(), $this->_request->PATH_INFO, $this->_request->PATH_INFO);
        $this->assertEquals($this->_request->getRequestUri(), $this->_request->REQUEST_URI, $this->_request->REQUEST_URI);
        if (isset($expEnvKey)) {
            $this->assertEquals($expected, $this->_request->$expEnvKey);
        }
    }

    public function testGetIsAlias()
    {
        $this->assertEquals('val1', $this->_request->get('var1'));
    }

    public function testSetIsAlias()
    {
        try {
            $this->_request->set('foo', 'bar');
            $this->fail('set() should alias to __set(), and throw an exception');
        } catch (Exception $e) {
            // success
        }
    }

    public function test__Isset()
    {
        $_POST['baz']   = 'boo';
        $_COOKIE['bal'] = 'peen';
        $this->_request->setParam('foo', 'bar');

        foreach ($_ENV as $envKey => $expected) {
            if (isset($_ENV[$envKey]) && !empty($_ENV[$envKey])) {
                $expEnvKey = $envKey;
                break;
            }
        }

        $this->assertTrue(isset($this->_request->foo));
        $this->assertTrue(isset($this->_request->var1));
        $this->assertTrue(isset($this->_request->baz));
        $this->assertTrue(isset($this->_request->bal));
        $this->assertTrue(isset($this->_request->REQUEST_TIME));
        $this->assertFalse(isset($this->_request->bogosity));
        if (isset($expEnvKey)) {
            $this->assertTrue(isset($this->_request->$expEnvKey));
        }
    }

    public function testHasIsAlias()
    {
        $this->assertTrue($this->_request->has('var1'));
    }

    public function test__SetThrowsException()
    {
        try {
            $this->_request->foo = 'bar';
            $this->fail('__set() should throw an exception');
        } catch (Exception $e) {
            // success
        }
    }
 
    public function testSetGetParam()
    {
        $this->_request->setParam('foo', 'bar');
        $this->assertEquals('bar', $this->_request->getParam('foo'));
    }
 
    public function testSetGetParams()
    {
        $params = array(
            'foo' => 'bar',
            'boo' => 'bah',
            'fee' => 'fi'
        );
        $this->_request->setParams($params);
        $received = $this->_request->getParams();
        $this->assertSame($params, array_intersect_assoc($params, $received));
    }

    public function testGetParamsWithNoGetOrPost()
    {
        unset($_GET, $_POST);
        $params = array(
            'foo' => 'bar',
            'boo' => 'bah',
            'fee' => 'fi'
        );
        $this->_request->setParams($params);
        $received = $this->_request->getParams();
        $this->assertSame($params, array_intersect_assoc($params, $received));
    }

    public function testGetParamsWithGetAndPost()
    {
        $_GET = array(
            'get' => true
        );
        $_POST = array(
            'post' => true
        );
        $params = array(
            'foo' => 'bar',
            'boo' => 'bah',
            'fee' => 'fi'
        );
        $this->_request->setParams($params);

        $expected = $params + $_GET + $_POST;
        $received = $this->_request->getParams();
        $this->assertSame($params, array_intersect_assoc($params, $received));
    }

    public function testConstructSetsRequestUri()
    {
        $_SERVER['REQUEST_URI'] = '/mycontroller/myaction?foo=bar';
        $request = new Zend_Controller_Request_Http();
        $this->assertEquals('/mycontroller/myaction?foo=bar', $request->getRequestUri());
    }

    public function testSetRequestUriDoesNotPassUriThroughUrldecode()
    {
        $request = new Zend_Controller_Request_Http();
        $request->setRequestUri('/foo/bar?foo=bar%20baz');
        $requestUri = $request->getRequestUri();
        $this->assertNotEquals('/foo/bar?foo=bar baz', $requestUri);
        $this->assertEquals('/foo/bar?foo=bar%20baz', $requestUri);
    }

    public function testGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $this->_request->getMethod());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $this->_request->getMethod());
    }
 
    public function testIsPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($this->_request->isPost());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->_request->isPost());
    }

    public function testIsGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->_request->isGet());
        $this->assertFalse($this->_request->isPost());
    }

    public function testIsPut()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertTrue($this->_request->isPut());
        $this->assertFalse($this->_request->isGet());
    }

    public function testIsDelete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertTrue($this->_request->isDelete());
        $this->assertFalse($this->_request->isGet());
    }

    public function testIsHead()
    {
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertTrue($this->_request->isHead());
        $this->assertFalse($this->_request->isGet());
    }

    public function testIsOptions()
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $this->assertTrue($this->_request->isOptions());
        $this->assertFalse($this->_request->isGet());
    }

    public function testGetRawBodyReturnsFalseWithNoPost()
    {
        $this->assertFalse($this->_request->getRawBody());
    }

    public function testGetQuery()
    {
        $this->assertEquals('val1', $this->_request->getQuery('var1'));

        $this->assertEquals('foo', $this->_request->getQuery('BAR', 'foo'));

        $expected = array('var1' => 'val1', 'var2' => 'val2');
        $this->assertEquals( $expected, $this->_request->getQuery());
    }
 

    public function testGetPost()
    {
        $_POST['post1'] = 'val1';
        $this->assertEquals('val1', $this->_request->getPost('post1'));

        $this->assertEquals('foo', $this->_request->getPost('BAR', 'foo'));

        $_POST['post2'] = 'val2';
        $expected = array('post1' => 'val1', 'post2' => 'val2');
        $this->assertEquals($expected, $this->_request->getPost());

    }
 
    public function testGetPathInfo()
    {
        $this->assertEquals('/news/3', $this->_request->getPathInfo(), 'Base URL: ' . var_export($this->_request->getBaseUrl(), 1));
    }
 
    public function testSetPathInfo()
    {
        $this->_request->setPathInfo('/archives/past/4');
        $this->assertEquals('/archives/past/4', $this->_request->getPathInfo());
    }

    public function testPathInfoNeedingBaseUrl()
    {
        $request = new Zend_Controller_Request_Http('http://localhost/test/index.php/ctrl-name/act-name');
        $this->assertEquals('/test/index.php/ctrl-name/act-name', $request->getRequestUri());
        $request->setBaseUrl('/test/index.php');
        $this->assertEquals('/test/index.php', $request->getBaseUrl());

        $requestUri = $request->getRequestUri();
        $baseUrl    = $request->getBaseUrl();
        $pathInfo   = substr($requestUri, strlen($baseUrl));
        $this->assertTrue($pathInfo ? true : false);

        $this->assertEquals('/ctrl-name/act-name', $request->getPathInfo(), "Expected $pathInfo;");
    }
 
    public function testGetSetAlias()
    {
        $this->_request->setAlias('controller', 'var1');
        $this->assertEquals('var1', $this->_request->getAlias('controller'));
    }
 
    public function testGetAliases()
    {
        $this->_request->setAlias('controller', 'var1');
        $this->_request->setAlias('action', 'var2');
        $this->assertSame(array('controller' => 'var1', 'action' => 'var2'), $this->_request->getAliases());
    }
 
    public function testGetRequestUri()
    {
        $this->assertEquals('/news/3?var1=val1&var2=val2', $this->_request->getRequestUri());
    }
 
    public function testSetRequestUri()
    {
        $this->_request->setRequestUri('/archives/past/4?set=this&unset=that');
        $this->assertEquals('/archives/past/4?set=this&unset=that', $this->_request->getRequestUri());
        $this->assertEquals('this', $this->_request->getQuery('set'));
        $this->assertEquals('that', $this->_request->getQuery('unset'));
    }

    public function testGetBaseUrl()
    {
        $this->assertSame('', $this->_request->getBaseUrl());
    }
 
    public function testSetBaseUrl()
    {
        $this->_request->setBaseUrl('/news');
        $this->assertEquals('/news', $this->_request->getBaseUrl());
    }

    public function testSetBaseUrlUsingPhpSelf()
    {
        $_SERVER['REQUEST_URI']     = '/index.php/news/3?var1=val1&var2=val2';
        $_SERVER['SCRIPT_NAME']     = '/home.php';
        $_SERVER['PHP_SELF']        = '/index.php/news/3';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/index.php', $request->getBaseUrl());
    }

    public function testSetBaseUrlUsingOrigScriptName()
    {
        $_SERVER['REQUEST_URI']     = '/index.php/news/3?var1=val1&var2=val2';
        $_SERVER['SCRIPT_NAME']     = '/home.php';
        $_SERVER['PHP_SELF']        = '/home.php';
        $_SERVER['ORIG_SCRIPT_NAME']= '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/index.php', $request->getBaseUrl());
    }

    public function testSetBaseUrlAutoDiscoveryUsingRequestUri()
    {
        $_SERVER['REQUEST_URI']     = '/index.php/news/3?var1=val1&var2=val2';
        $_SERVER['PHP_SELF']        = '/index.php/news/3';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/index.php', $request->getBaseUrl());
    }
 
    public function testSetBaseUrlAutoDiscoveryUsingXRewriteUrl()
    {
        unset($_SERVER['REQUEST_URI']);
        $_SERVER['HTTP_X_REWRITE_URL'] = '/index.php/news/3?var1=val1&var2=val2';
        $_SERVER['PHP_SELF']           = '/index.php/news/3';
        $_SERVER['SCRIPT_FILENAME']    = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/index.php', $request->getBaseUrl());
    }

    public function testSetBaseUrlAutoDiscoveryUsingOrigPathInfo()
    {
        unset($_SERVER['REQUEST_URI']);
        $_SERVER['ORIG_PATH_INFO']  = '/index.php/news/3';
        $_SERVER['QUERY_STRING']    = 'var1=val1&var2=val2';
        $_SERVER['PHP_SELF']        = '/index.php/news/3';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/index.php', $request->getBaseUrl());
    }

    public function testGetSetBasePath()
    {
        $this->_request->setBasePath('/news');
        $this->assertEquals('/news', $this->_request->getBasePath());
    }
 
    public function testBasePathAutoDiscovery()
    {
        $_SERVER['REQUEST_URI']     = '/html/index.php/news/3?var1=val1&var2=val2';
        $_SERVER['PHP_SELF']        = '/html/index.php/news/3';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
        $_GET = array(
            'var1' => 'val1',
            'var2' => 'val2'
        );
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/html', $request->getBasePath(), $request->getBaseUrl());
    }

    public function testBasePathAutoDiscoveryWithPhpFile()
    {
        $_SERVER['REQUEST_URI']     = '/dir/action';
        $_SERVER['PHP_SELF']        = '/dir/index.php';
        $_SERVER['SCRIPT_FILENAME'] = '/var/web/dir/index.php';
        $request = new Zend_Controller_Request_Http();

        $this->assertEquals('/dir', $request->getBasePath(), $request->getBaseUrl());
    }

    public function testGetCookie()
    {
        $_COOKIE['foo'] = 'bar';
        $this->assertSame('bar', $this->_request->getCookie('foo'));
        $this->assertEquals('foo', $this->_request->getCookie('BAR', 'foo'));
        $this->assertEquals($_COOKIE, $this->_request->getCookie());
    }
 
    public function testGetServer()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->assertEquals($_SERVER['REQUEST_METHOD'], $this->_request->getServer('REQUEST_METHOD'));
        }
        $this->assertEquals('foo', $this->_request->getServer('BAR', 'foo'));
        $this->assertEquals($_SERVER, $this->_request->getServer());
    }
 
    public function testGetEnv()
    {
        if (isset($_ENV['PATH'])) {
            $this->assertEquals($_ENV['PATH'], $this->_request->getEnv('PATH'));
        }
        $this->assertEquals('foo', $this->_request->getEnv('BAR', 'foo'));
        $this->assertEquals($_ENV, $this->_request->getEnv());
    }

    public function testGetHeader()
    {
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'UTF-8';
        $_SERVER['HTTP_CONTENT_TYPE']    = 'text/json';

        $this->assertEquals('UTF-8', $this->_request->getHeader('Accept-Encoding'));
        $this->assertEquals('text/json', $this->_request->getHeader('Content-Type'));

        $this->assertFalse($this->_request->getHeader('X-No-Such-Thing'));
    }

    public function testGetHeaderThrowsExceptionWithNoInput()
    {
        try {
            // Suppressing warning
            $header = @$this->_request->getHeader();
            $this->fail('getHeader() should fail with no arguments)');
        } catch (Exception $e) {
            // success
        }
    }

    public function testIsXmlHttpRequest()
    {
        $this->assertFalse($this->_request->isXmlHttpRequest());
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->assertTrue($this->_request->isXmlHttpRequest());
    }

    public function testSetNullParamUnsetsKey()
    {
        $this->_request->setParam('foo', 'bar');
        $this->assertEquals('bar', $this->_request->getParam('foo'));
        $this->_request->setParam('foo', null);
        $params = $this->_request->getParams();
        $this->assertFalse(isset($params['foo']));
    }

    public function testSetNullParamsUnsetsKeys()
    {
        $this->_request->setParams(array('foo' => 'bar', 'bar' => 'baz'));
        $this->assertEquals('bar', $this->_request->getParam('foo'));
        $this->assertEquals('baz', $this->_request->getParam('bar'));
        $this->_request->setParams(array('foo' => null));
        $params = $this->_request->getParams();
        $this->assertFalse(isset($params['foo']));
        $this->assertTrue(isset($params['bar']));
    }

    public function testGetAliasedParamZF2455()
    {
        $this->_request->setParam('controller', 'value');
        $this->_request->setAlias('var1', 'controller');
        
        $this->assertEquals('value', $this->_request->getParam('controller'));
        $this->assertEquals('value', $this->_request->getParam('var1'));
    }

    public function testCanDetectFlashRequests()
    {
        $this->assertFalse($this->_request->isFlashRequest());
        $_SERVER['HTTP_USER_AGENT'] = 'Shockwave Flash';
        $this->assertTrue($this->_request->isFlashRequest());
    }

    /**
     * ZF-1798
     */
    public function testGetAndPostBothInDefaultParamSources()
    {
        $this->assertEquals(array('_GET', '_POST'), $this->_request->getParamSources());
    }

    /**
     * ZF-1798
     */
    public function testCanSetParamSources()
    {
        $this->testGetAndPostBothInDefaultParamSources();
        $this->_request->setParamSources(array());
        $this->assertSame(array(), $this->_request->getParamSources());
        $this->_request->setParamSources(array('_GET'));
        $this->assertSame(array('_GET'), $this->_request->getParamSources());
    }

    /**
     * ZF-1798
     */
    public function testParamSourcesHonoredByGetParam()
    {
        $_GET  = array('foo' => 'bar');
        $_POST = array('foo' => 'baz');
        $this->_request->setParamSources(array('_POST'));
        $this->assertEquals('baz', $this->_request->getParam('foo'));
    }
}

// Call Zend_Controller_Request_HttpTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Controller_Request_HttpTest::main") {
    Zend_Controller_Request_HttpTest::main();
}
