<?php
// Call Zend_View_Helper_HeadTitleTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_View_Helper_HeadTitleTest::main");
}

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/TestHelper.php';

/** Zend_View_Helper_HeadTitle */
require_once 'Zend/View/Helper/HeadTitle.php';

/** Zend_View_Helper_Placeholder_Registry */
require_once 'Zend/View/Helper/Placeholder/Registry.php';

/** Zend_Registry */
require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_HeadTitle.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 */
class Zend_View_Helper_HeadTitleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_View_Helper_HeadTitle
     */
    public $helper;

    /**
     * @var string
     */
    public $basePath;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_View_Helper_HeadTitleTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $regKey = Zend_View_Helper_Placeholder_Registry::REGISTRY_KEY;
        if (Zend_Registry::isRegistered($regKey)) {
            $registry = Zend_Registry::getInstance();
            unset($registry[$regKey]);
        }
        $this->basePath = dirname(__FILE__) . '/_files/modules';
        $this->helper = new Zend_View_Helper_HeadTitle();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->helper);
    }

    public function testNamespaceRegisteredInPlaceholderRegistryAfterInstantiation()
    {
        $registry = Zend_View_Helper_Placeholder_Registry::getRegistry();
        if ($registry->containerExists('Zend_View_Helper_HeadTitle')) {
            $registry->deleteContainer('Zend_View_Helper_HeadTitle');
        }
        $this->assertFalse($registry->containerExists('Zend_View_Helper_HeadTitle'));
        $helper = new Zend_View_Helper_HeadTitle();
        $this->assertTrue($registry->containerExists('Zend_View_Helper_HeadTitle'));
    }

    public function testHeadTitleReturnsObjectInstance()
    {
        $placeholder = $this->helper->headTitle();
        $this->assertTrue($placeholder instanceof Zend_View_Helper_HeadTitle);
    }

    public function testCanSetTitleViaHeadTitle()
    {
        $placeholder = $this->helper->headTitle('Foo Bar', 'SET');
        $this->assertContains('Foo Bar', $placeholder->toString());
    }

    public function testCanAppendTitleViaHeadTitle()
    {
        $placeholder = $this->helper->headTitle('Foo');
        $placeholder = $this->helper->headTitle('Bar');
        $this->assertContains('FooBar', $placeholder->toString());
    }

    public function testCanPrependTitleViaHeadTitle()
    {
        $placeholder = $this->helper->headTitle('Foo');
        $placeholder = $this->helper->headTitle('Bar', 'PREPEND');
        $this->assertContains('BarFoo', $placeholder->toString());
    }

    public function testReturnedPlaceholderToStringContainsFullTitleElement()
    {
        $placeholder = $this->helper->headTitle('Foo');
        $placeholder = $this->helper->headTitle('Bar', 'APPEND')->setSeparator(' :: ');
        $this->assertEquals('<title>Foo :: Bar</title>', $placeholder->toString());
    }

    public function testToStringEscapesEntries()
    {
        $this->helper->headTitle('<script type="text/javascript">alert("foo");</script>');
        $string = $this->helper->toString();
        $this->assertNotContains('<script', $string);
        $this->assertNotContains('</script>', $string);
    }

    public function testToStringEscapesSeparator()
    {
        $this->helper->headTitle('Foo')
                     ->headTitle('Bar')
                     ->setSeparator(' <br /> ');
        $string = $this->helper->toString();
        $this->assertNotContains('<br />', $string);
        $this->assertContains('Foo', $string);
        $this->assertContains('Bar', $string);
        $this->assertContains('br /', $string);
    }

    public function testIndentationIsHonored()
    {
        $this->helper->setIndent(4);
        $this->helper->headTitle('foo');
        $string = $this->helper->toString();

        $this->assertContains('    <title>', $string);
    }

    public function testAutoEscapeIsHonored()
    {
        $this->helper->headTitle('Some Title &copyright;');
        $this->assertEquals('<title>Some Title &amp;copyright;</title>', $this->helper->toString());

        $this->assertTrue($this->helper->headTitle()->getAutoEscape());
        $this->helper->headTitle()->setAutoEscape(false);
        $this->assertFalse($this->helper->headTitle()->getAutoEscape());


        $this->assertEquals('<title>Some Title &copyright;</title>', $this->helper->toString());
    }

    public function testZF2918()
    {
        $this->helper->headTitle('Some Title');
        $this->helper->setPrefix('Prefix: ');
        $this->helper->setPostfix(' :Postfix');

        $this->assertEquals('<title>Prefix: Some Title :Postfix</title>', $this->helper->toString());
    }
}

// Call Zend_View_Helper_HeadTitleTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_View_Helper_HeadTitleTest::main") {
    Zend_View_Helper_HeadTitleTest::main();
}
