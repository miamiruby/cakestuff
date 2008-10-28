<?php
/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Controller_Router_Route_ChainTest::main');
}

require_once dirname(__FILE__) . '/../../../../TestHelper.php';

/** Zend_Controller_Router_Route_Chain */
require_once 'Zend/Controller/Router/Route/Chain.php';

/** Zend_Controller_Router_Route */
require_once 'Zend/Controller/Router/Route.php';

/** Zend_Controller_Router_Route_Static */
require_once 'Zend/Controller/Router/Route/Static.php';

/** Zend_Controller_Router_Route_Static */
require_once 'Zend/Controller/Router/Route/Regex.php';

/** Zend_Controller_Router_Route_Hostname */
require_once 'Zend/Controller/Router/Route/Hostname.php';

/** Zend_Controller_Request_Http */
require_once 'Zend/Controller/Request/Http.php';

/** Zend_Uri_Http */
require_once 'Zend/Uri/Http.php';

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */
class Zend_Controller_Router_Route_ChainTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Zend_Controller_Router_Route_ChainTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }
 
    public function testChaining()
    {
        $request = new Zend_Controller_Router_ChainTest_Request('http://localhost/foo/bar');

        $foo = new Zend_Controller_Router_Route('foo');
        $bar = new Zend_Controller_Router_Route('bar');

        $chain = $foo->chain($bar);

        $this->assertType('Zend_Controller_Router_Route_Chain', $chain);
    }

    public function testChainingMatch()
    {
        $chain = new Zend_Controller_Router_Route_Chain();
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 1));
        $bar = new Zend_Controller_Router_Route_Static('bar', array('bar' => 2));

        $chain->chain($foo)->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bla');
        $res = $chain->match($request);

        $this->assertFalse($res);
        
        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bar');
        $res = $chain->match($request);
        
        $this->assertEquals(1, $res['foo']);
        $this->assertEquals(2, $res['bar']);
    }

    public function testChainingShortcutMatch()
    {
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 1));
        $bar = new Zend_Controller_Router_Route_Static('bar', array('bar' => 2, 'controller' => 'foo', 'action' => 'bar'));

        $chain = $foo->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bar');
        $res = $chain->match($request);
        
        $this->assertEquals(1, $res['foo']);
        $this->assertEquals(2, $res['bar']);
    }

    public function testChainingMatchFailure()
    {
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 1));
        $bar = new Zend_Controller_Router_Route_Static('bar', array('bar' => 2, 'controller' => 'foo', 'action' => 'bar'));

        $chain = $foo->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://nope.zend.com/bar');
        $res = $chain->match($request);

        $this->assertFalse($res);
    }

    public function testChainingVariableOverriding()
    {
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 1, 'controller' => 'foo', 'module' => 'foo'));
        $bar = new Zend_Controller_Router_Route('bar', array('bar' => 2, 'controller' => 'bar', 'action' => 'bar'));

        $chain = $foo->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bar');
        $res = $chain->match($request);

        $this->assertEquals('foo', $res['module']);
        $this->assertEquals('bar', $res['controller']);
        $this->assertEquals('bar', $res['action']);
    }

    public function testChainingSeparatorOverriding()
    {
        $this->markTestSkipped('Route features not ready');
        
        $foo = new Zend_Controller_Router_Route('foo', array('foo' => 1));
        $bar = new Zend_Controller_Router_Route('bar', array('bar' => 2));
        $baz = new Zend_Controller_Router_Route('baz', array('baz' => 3));

        $chain = $foo->chain($bar, '.');

        $res = $chain->match('foo.bar');
        $this->assertType('array', $res);

        $res = $chain->match('foo/bar');
        $this->assertEquals(false, $res);

        $chain->chain($baz, ':');

        $res = $chain->match('foo.bar:baz');
        $this->assertType('array', $res);
    }

    public function testI18nChaining()
    {
        $this->markTestSkipped('Route features not ready');
        
        $lang = new Zend_Controller_Router_Route(':lang', array('lang' => 'en'));
        $profile = new Zend_Controller_Router_Route('user/:id', array('controller' => 'foo', 'action' => 'bar'));

        $chain = $lang->chain($profile);

        $res = $chain->match('en/user/1');

        $this->assertEquals('en', $res['lang']);
        $this->assertEquals('1', $res['id']);
    }

    public function testChainingAssembleWithStatic()
    {
        $chain = new Zend_Controller_Router_Route_Chain();

        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 'foo'));
        $bar = new Zend_Controller_Router_Route_Static('bar', array('bar' => 'bar'));

        $chain->chain($foo)->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bar');
        $res = $chain->match($request);
        
        $this->assertType('array', $res);
        $this->assertRegexp('#[^a-z0-9]?www\.zend\.com/bar$#i', $chain->assemble());
    }

    public function testChainingAssembleWithRegex()
    {
        $chain = new Zend_Controller_Router_Route_Chain();

        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 'foo'));
        $bar = new Zend_Controller_Router_Route_Regex('bar', array('bar' => 'bar'), array(), 'bar');

        $chain->chain($foo)->chain($bar);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/bar');
        $res = $chain->match($request);
        
        $this->assertType('array', $res);
        $this->assertRegexp('#[^a-z0-9]?www\.zend\.com/bar$#i', $chain->assemble());
    }
    
    public function testChainingReuse()
    {
        $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('foo' => 'foo'));
        $profile = new Zend_Controller_Router_Route('user/:id', array('controller' => 'prof'));
        $article = new Zend_Controller_Router_Route('article/:id', array('controller' => 'art', 'action' => 'art'));

        $profileChain = $foo->chain($profile);
        $articleChain = $foo->chain($article);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/user/1');
        $res = $profileChain->match($request);
        
        $this->assertType('array', $res);
        $this->assertEquals('prof', $res['controller']);

        $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/article/1');
        $res = $articleChain->match($request);
        
        $this->assertType('array', $res);
        $this->assertEquals('art', $res['controller']);
        $this->assertEquals('art', $res['action']);
    }

}

/**
 * Zend_Controller_Router_ChainTest_Request - request object for router testing
 *
 * @uses Zend_Controller_Request_Interface
 */
class Zend_Controller_Router_ChainTest_Request extends Zend_Controller_Request_Http
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

if (PHPUnit_MAIN_METHOD == "Zend_Controller_Router_Route_ChainTest::main") {
    Zend_Controller_Router_Route_ChainTest::main();
}
