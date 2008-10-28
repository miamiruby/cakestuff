<?php
/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */

/** Zend_Controller_Router_Rewrite */
require_once 'Zend/Controller/Router/Rewrite.php';

/** Zend_Controller_Dispatcher_Standard */
require_once 'Zend/Controller/Dispatcher/Standard.php';

/** Zend_Controller_Front */
require_once 'Zend/Controller/Front.php';

/** Zend_Controller_Request_Http */
require_once 'Zend/Controller/Request/Http.php';

/** Zend_Controller_Router_Route */
require_once 'Zend/Controller/Router/Route.php';

/** Zend_Controller_Router_Route_Chain */
require_once 'Zend/Controller/Router/Route/Chain.php';

/** Zend_Controller_Router_Route_Hostname */
require_once 'Zend/Controller/Router/Route/Hostname.php';

/** PHPUnit test case */
require_once 'PHPUnit/Framework/TestCase.php';

require_once 'PHPUnit/Runner/Version.php';

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */
class Zend_Controller_Router_RewriteTest extends PHPUnit_Framework_TestCase
{
    protected $_router;

    public function setUp() {
        $this->_router = new Zend_Controller_Router_Rewrite();
        $front = Zend_Controller_Front::getInstance();
        $front->resetInstance();
        $front->setDispatcher(new Zend_Controller_Router_RewriteTest_Dispatcher());
        $front->setRequest(new Zend_Controller_Router_RewriteTest_Request());
        $this->_router->setFrontController($front);
    }

    public function tearDown() {
        unset($this->_router);
    }

    public function testAddRoute()
    {
        $this->_router->addRoute('archive', new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+')));
        $routes = $this->_router->getRoutes();

        $this->assertEquals(1, count($routes));
        $this->assertType('Zend_Controller_Router_Route', $routes['archive']);

        $this->_router->addRoute('register', new Zend_Controller_Router_Route('register/:action', array('controller' => 'profile', 'action' => 'register')));
        $routes = $this->_router->getRoutes();

        $this->assertEquals(2, count($routes));
        $this->assertType('Zend_Controller_Router_Route', $routes['register']);
    }

    public function testAddRoutes()
    {
        $routes = array(
            'archive' => new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+')),
            'register' => new Zend_Controller_Router_Route('register/:action', array('controller' => 'profile', 'action' => 'register'))
        );
        $this->_router->addRoutes($routes);

        $values = $this->_router->getRoutes();

        $this->assertEquals(2, count($values));
        $this->assertType('Zend_Controller_Router_Route', $values['archive']);
        $this->assertType('Zend_Controller_Router_Route', $values['register']);
    }

    public function testHasRoute()
    {
        $this->_router->addRoute('archive', new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+')));

        $this->assertEquals(true, $this->_router->hasRoute('archive'));
        $this->assertEquals(false, $this->_router->hasRoute('bogus'));
    }

    public function testGetRoute()
    {
        $archive = new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+'));
        $this->_router->addRoute('archive', $archive);

        $route = $this->_router->getRoute('archive');

        $this->assertType('Zend_Controller_Router_Route', $route);
        $this->assertSame($route, $archive);
    }

    public function testRemoveRoute()
    {
        $this->_router->addRoute('archive', new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+')));

        $route = $this->_router->getRoute('archive');

        $this->_router->removeRoute('archive');

        $routes = $this->_router->getRoutes();
        $this->assertEquals(0, count($routes));

        try {
            $route = $this->_router->removeRoute('archive');
        } catch (Zend_Controller_Router_Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
            return true;
        }

        $this->fail();
    }

    public function testGetNonExistentRoute()
    {
        try {
            $route = $this->_router->getRoute('bogus');
        } catch (Zend_Controller_Router_Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
            return true;
        }

        $this->fail();
    }

    public function testRoute()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request();

        $token = $this->_router->route($request);

        $this->assertType('Zend_Controller_Request_Http', $token);
    }

    public function testRouteWithIncorrectRequest()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request_Incorrect();

        try {
            $token = $this->_router->route($request);
            $this->fail('Should throw an Exception');
        } catch (Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
        }
    }

    public function testDefaultRoute()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request();

        $token = $this->_router->route($request);

        $routes = $this->_router->getRoutes();
        $this->assertType('Zend_Controller_Router_Route_Module', $routes['default']);
    }

    public function testDefaultRouteWithEmptyAction()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/ctrl');

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('defact', $token->getActionName());
    }

    public function testEmptyRoute()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/');

        $this->_router->removeDefaultRoutes();
        $this->_router->addRoute('empty', new Zend_Controller_Router_Route('', array('controller' => 'ctrl', 'action' => 'act')));

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('act', $token->getActionName());
    }

    public function testEmptyPath()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/');

        $this->_router->removeDefaultRoutes();
        $this->_router->addRoute('catch-all', new Zend_Controller_Router_Route(':controller/:action/*', array('controller' => 'ctrl', 'action' => 'act')));

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('act', $token->getActionName());
    }

    public function testEmptyPathWithWildcardRoute()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/');

        $this->_router->removeDefaultRoutes();
        $this->_router->addRoute('catch-all', new Zend_Controller_Router_Route('*', array('controller' => 'ctrl', 'action' => 'act')));

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('act', $token->getActionName());
    }

    public function testRouteNotMatched()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/archive/action/bogus');

        $this->_router->addRoute('default', new Zend_Controller_Router_Route(':controller/:action'));

        $token = $this->_router->route($request);

        $this->assertNull($token->getControllerName());
        $this->assertNull($token->getActionName());
    }

    public function testDefaultRouteMatched()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/ctrl/act');

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('act', $token->getActionName());
    }

    public function testDefaultRouteMatchedWithControllerOnly()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/ctrl');

        $token = $this->_router->route($request);

        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('defact', $token->getActionName());
    }

    public function testFirstRouteMatched()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/archive/2006');

        $this->_router->addRoute('archive', new Zend_Controller_Router_Route('archive/:year', array('year' => '2006', 'controller' => 'archive', 'action' => 'show'), array('year' => '\d+')));
        $this->_router->addRoute('register', new Zend_Controller_Router_Route('register/:action', array('controller' => 'profile', 'action' => 'register')));

        $token = $this->_router->route($request);

        $this->assertEquals('archive', $token->getControllerName());
        $this->assertEquals('show', $token->getActionName());
    }

    public function testGetCurrentRoute()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/ctrl/act');

        try {
            $route = $this->_router->getCurrentRoute();
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
        }

        try {
            $route = $this->_router->getCurrentRouteName();
            $this->fail();
        } catch (Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
        }

        $token = $this->_router->route($request);

        try {
            $route = $this->_router->getCurrentRoute();
            $name = $this->_router->getCurrentRouteName();
        } catch (Exception $e) {
            $this->fail('Current route is not set');
        }

        $this->assertEquals('default', $name);
        $this->assertType('Zend_Controller_Router_Route_Module', $route);
    }

    public function testAddConfig()
    {
        require_once 'Zend/Config/Ini.php';
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'routes.ini';
        $config = new Zend_Config_Ini($file, 'testing');

        $this->_router->addConfig($config, 'routes');

        $this->assertType('Zend_Controller_Router_Route_Static', $this->_router->getRoute('news'));
        $this->assertType('Zend_Controller_Router_Route', $this->_router->getRoute('archive'));

        try {
            $this->_router->addConfig($config, 'database');
        } catch (Exception $e) {
            $this->assertType('Zend_Controller_Router_Exception', $e);
            return true;
        }
    }

    public function testAddConfigWithoutSection()
    {
        require_once 'Zend/Config/Ini.php';
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'routes.ini';
        $config = new Zend_Config_Ini($file, 'testing');

        $this->_router->addConfig($config->routes);

        $this->assertType('Zend_Controller_Router_Route_Static', $this->_router->getRoute('news'));
        $this->assertType('Zend_Controller_Router_Route', $this->_router->getRoute('archive'));
    }

    public function testAddConfigWithRootNode()
    {
        require_once 'Zend/Config/Ini.php';
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'routes-root.ini';
        $config = new Zend_Config_Ini($file, 'routes');

        $this->_router->addConfig($config);

        $this->assertType('Zend_Controller_Router_Route_Static', $this->_router->getRoute('news'));
        $this->assertType('Zend_Controller_Router_Route', $this->_router->getRoute('archive'));
    }

    public function testRemoveDefaultRoutes()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/ctrl/act');
        $this->_router->removeDefaultRoutes();

        $token = $this->_router->route($request);

        $routes = $this->_router->getRoutes();
        $this->assertEquals(0, count($routes));
    }

    public function testDefaultRouteMatchedWithModules()
    {
        Zend_Controller_Front::getInstance()->setControllerDirectory(array(
            'default' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files',
            'mod'     => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'Admin',
        ));
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/mod/ctrl/act');
        $token = $this->_router->route($request);

        $this->assertEquals('mod',  $token->getModuleName());
        $this->assertEquals('ctrl', $token->getControllerName());
        $this->assertEquals('act',  $token->getActionName());
    }

    public function testRouteCompatDefaults()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/');

        $token = $this->_router->route($request);

        $this->assertEquals('default', $token->getModuleName());
        $this->assertEquals('defctrl', $token->getControllerName());
        $this->assertEquals('defact', $token->getActionName());
    }

    public function testDefaultRouteWithEmptyControllerAndAction()
    {
        Zend_Controller_Front::getInstance()->setControllerDirectory(array(
            'default' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files',
            'mod'     => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'Admin',
        ));
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/mod');

        $token = $this->_router->route($request);

        $this->assertEquals('mod', $token->getModuleName());
        $this->assertEquals('defctrl', $token->getControllerName());
        $this->assertEquals('defact', $token->getActionName());
    }

    public function testNumericallyIndexedReturnParams()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/archive/2006');

        $this->_router->addRoute('test', new Zend_Controller_Router_Route_Mockup());

        $token = $this->_router->route($request);

        $this->assertEquals('index', $token->getControllerName());
        $this->assertEquals('index', $token->getActionName());
        $this->assertEquals('first_parameter_value', $token->getParam(0));
    }

    public function testUrlValuesHandling1() // See ZF-3212 and ZF-3219
    {
        $this->_router->addRoute('foo', new Zend_Controller_Router_Route(':lang/foo', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));
        $this->_router->addRoute('bar', new Zend_Controller_Router_Route(':lang/bar', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));

        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/nl/bar');
        $token = $this->_router->route($request);

        $this->assertEquals('nl/foo', $this->_router->getRoute('foo')->assemble());
        $this->assertEquals('nl/bar', $this->_router->getRoute('bar')->assemble());
    }

    public function testUrlValuesHandling2() // See ZF-3212 and ZF-3219
    {
        $this->_router->addRoute('foo', new Zend_Controller_Router_Route(':lang/foo', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));
        $this->_router->addRoute('bar', new Zend_Controller_Router_Route(':lang/bar', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));

        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/en/foo');
        $token = $this->_router->route($request);

        $this->assertEquals('en/foo', $this->_router->getRoute('foo')->assemble());
        $this->assertEquals('nl/bar', $this->_router->getRoute('bar')->assemble());
    }

    public function testUrlValuesHandling3() // See ZF-3212 and ZF-3219
    {
        $this->_router->addRoute('foo', new Zend_Controller_Router_Route(':lang/foo', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));
        $this->_router->addRoute('bar', new Zend_Controller_Router_Route(':lang/bar', array('lang' => 'nl', 'controller' => 'index', 'action' => 'index')));

        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/en/bar');
        $token = $this->_router->route($request);

        $this->assertEquals('nl/foo', $this->_router->getRoute('foo')->assemble());
        $this->assertEquals('en/bar', $this->_router->getRoute('bar')->assemble());
    }

    public function testRouteRequestInterface()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/en/foo');
        $front = $this->_router->getFrontController()->setRequest($request);

        $this->_router->addRoute('req', new Zend_Controller_Router_Route_Interface_Mockup());
        $routeRequest = $this->_router->getRoute('req')->getRequest();

        $this->assertType('Zend_Controller_Request_Abstract', $request);
        $this->assertType('Zend_Controller_Request_Abstract', $routeRequest);
        $this->assertSame($request, $routeRequest);
    }

    public function testRoutingVersion2Routes()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/en/bar');
        $request->setParam('path', 'v2test');
        
        $route = new Zend_Controller_RouterTest_RouteV2_Stub('not-important');
        $this->_router->addRoute('foo', $route);

        $token = $this->_router->route($request);

        $this->assertEquals('v2test', $token->getParam('path'));
    }
        
    public function testRoutingChainedRoutes()
    {
        $this->markTestSkipped('Route features not ready');
        
        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/foo/bar');

        $foo = new Zend_Controller_Router_Route('foo', array('foo' => true));
        $bar = new Zend_Controller_Router_Route('bar', array('bar' => true, 'controller' => 'foo', 'action' => 'bar'));

        $chain = new Zend_Controller_Router_Route_Chain();
        $chain->chain($foo)->chain($bar);

        $this->_router->addRoute('foo-bar', $chain);

        $token = $this->_router->route($request);

        $this->assertEquals('foo', $token->getControllerName());
        $this->assertEquals('bar', $token->getActionName());
        $this->assertEquals(true, $token->getParam('foo'));
        $this->assertEquals(true, $token->getParam('bar'));
    }
    
    public function testRouteWithHostnameChain()
    {
        $request = new Zend_Controller_Router_RewriteTest_Request('http://www.zend.com/bar');
        
        $foo = new Zend_Controller_Router_Route_Hostname('nope.zend.com', array('module' => 'nope-bla', 'bogus' => 'bogus'));
        $bar = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('module' => 'www-bla'));
        
        $bla = new Zend_Controller_Router_Route_Static('bar', array('controller' => 'foo', 'action' => 'bar'));
        
        $chainMatch = new Zend_Controller_Router_Route_Chain();
        $chainMatch->chain($bar)->chain($bla);
        
        $chainNoMatch = new Zend_Controller_Router_Route_Chain();
        $chainNoMatch->chain($foo)->chain($bla);
               
        $this->_router->addRoute('match',    $chainMatch);
        $this->_router->addRoute('no-match', $chainNoMatch);
        
        $token = $this->_router->route($request);

        $this->assertEquals('www-bla', $token->getModuleName());
        $this->assertEquals('foo', $token->getControllerName());
        $this->assertEquals('bar', $token->getActionName());
        $this->assertNull($token->getParam('bogus'));
    } 
    
    public function testAssemblingWithHostnameHttp()
    {
        $this->markTestSkipped('Router features not ready');
        
        $route = new Zend_Controller_Router_Route_Hostname('www.zend.com');

        $this->_router->addRoute('hostname-route', $route);

        $this->assertEquals('http://www.zend.com', $this->_router->assemble(array(), 'hostname-route'));
    }
    
    public function testAssemblingWithHostnameHttps()
    {
        $this->markTestSkipped('Router features not ready');
        
        $backupServer = $_SERVER;
        $_SERVER['HTTPS'] = 'on';
        
        $route = new Zend_Controller_Router_Route_Hostname('www.zend.com');

        $this->_router->addRoute('hostname-route', $route);

        $this->assertEquals('https://www.zend.com', $this->_router->assemble(array(), 'hostname-route'));
        
        $_SERVER = $backupServer;
    }
    
    public function testAssemblingWithHostnameThroughChainHttp()
    {
        $this->markTestSkipped('Router features not ready');
        
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com');
        $bar = new Zend_Controller_Router_Route_Static('bar');

        $chain = new Zend_Controller_Router_Route_Chain();
        $chain->chain($foo)->chain($bar);
        
        $this->_router->addRoute('foo-bar', $chain);

        $this->assertEquals('http://www.zend.com/bar', $this->_router->assemble(array(), 'foo-bar'));
    }
    
    public function testAssemblingWithHostnameWithChainHttp()
    {
        $this->markTestSkipped('Router features not ready');
        
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com');
        $bar = new Zend_Controller_Router_Route_Static('bar');

        $chain = $foo->chain($bar);
        
        $this->_router->addRoute('foo-bar', $chain);

        $this->assertEquals('http://www.zend.com/bar', $this->_router->assemble(array(), 'foo-bar'));
    }
    
    public function testAssemblingWithNonFirstHostname()
    {
        $this->markTestSkipped('Router features not ready');
        
        $foo = new Zend_Controller_Router_Route_Static('bar');
        $bar = new Zend_Controller_Router_Route_Hostname('www.zend.com');

        $foo->chain($bar);
        
        $this->_router->addRoute('foo-bar', $foo);

        $this->assertEquals('bar/www.zend.com', $this->_router->assemble(array(), 'foo-bar'));
    }

    /**
     * @see ZF-3922
     */
    public function testRouteShouldMatchEvenWithTrailingSlash()
    {
        $route = new Zend_Controller_Router_Route(
            'blog/articles/:id', 
            array(
                'controller' => 'blog',
                'action'     => 'articles',
                'id'         => 0,
            ), 
            array(
                'id' => '[0-9]+',
            )
        );
        $this->_router->addRoute('article-id', $route);

        $request = new Zend_Controller_Router_RewriteTest_Request('http://localhost/blog/articles/2006/');
        $token   = $this->_router->route($request);

        $this->assertSame('article-id', $this->_router->getCurrentRouteName());

        $this->assertEquals('2006', $token->getParam('id', false));
    }
}


/**
 * Zend_Controller_Router_RewriteTest_Request - request object for router testing
 *
 * @uses Zend_Controller_Request_Interface
 */
class Zend_Controller_Router_RewriteTest_Request extends Zend_Controller_Request_Http
{
    protected $_host;
    protected $_port;
    
    public function __construct($uri = null)
    {
        if (null === $uri) {
            $uri = 'http://localhost/foo/bar/baz/2';
        }

        $uri = Zend_Uri_Http::fromString($uri);
        $this->_host = $uri->getHost();
        $this->_port = $uri->getPort();
        
        parent::__construct($uri);
    }

    public function getHttpHost() {
        $return = $this->_host;
        if ($this->_port)  $return .= ':' . $this->_port;
        return $return;
    }
}

/**
 * Zend_Controller_RouterTest_Dispatcher
 */
class Zend_Controller_Router_RewriteTest_Dispatcher extends Zend_Controller_Dispatcher_Standard
{
    public function getDefaultControllerName()
    {
        return 'defctrl';
    }

    public function getDefaultAction()
    {
        return 'defact';
    }
}

/**
 * Zend_Controller_RouterTest_Request_Incorrect - request object for router testing
 *
 * @uses Zend_Controller_Request_Abstract
 */
class Zend_Controller_Router_RewriteTest_Request_Incorrect extends Zend_Controller_Request_Abstract
{
}

/**
 * Zend_Controller_RouterTest_RouteV2_Stub - request object for router testing
 *
 * @uses Zend_Controller_Request_Abstract
 */
class Zend_Controller_RouterTest_RouteV2_Stub implements Zend_Controller_Router_Route_Interface
{
    public function getVersion() { return 2; }
    public function match($request) {
        return array('path', $request->getParam('path'));
    }

    public static function getInstance(Zend_Config $config) {}
    public function assemble($data = array(), $reset = false, $encode = false) {}
}

class Zend_Controller_Router_Route_Mockup implements Zend_Controller_Router_Route_Interface
{
    public function match($path, $partial = null)
    {
        return array(
            "controller" => "index",
            "action" => "index",
            0 => "first_parameter_value"
        );
    }
    public static function getInstance(Zend_Config $config) {}
    public function assemble($data = array(), $reset = false, $encode = false) {}
}

class Zend_Controller_Router_Route_Interface_Mockup implements Zend_Controller_Router_Route_Interface
{
    protected $_request;

    public function match($path, $partial = null) {}
    public static function getInstance(Zend_Config $config) {}
    public function assemble($data = array(), $reset = false, $encode = false) {}

    public function setRequest($request) {
        $this->_request = $request;
    }
    public function getRequest() {
        return $this->_request;
    }
}
