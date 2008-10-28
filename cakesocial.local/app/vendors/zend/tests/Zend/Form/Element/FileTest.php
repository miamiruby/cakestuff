<?php
// Call Zend_Form_Element_FileTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Form_Element_FileTest::main");
}

require_once dirname(__FILE__) . '/../../../TestHelper.php';

require_once 'Zend/Form/Element/File.php';
require_once 'Zend/File/Transfer/Adapter/Abstract.php';
require_once 'Zend/Validate/File/Upload.php';
require_once 'Zend/Form/SubForm.php';
require_once 'Zend/View.php';

/**
 * Test class for Zend_Form_Element_File
 */
class Zend_Form_Element_FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Form_Element_FileTest");
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
        $this->element = new Zend_Form_Element_File('foo');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
    }

    public function testElementShouldProxyToParentForDecoratorPluginLoader()
    {
        $loader = $this->element->getPluginLoader('decorator');
        $paths = $loader->getPaths('Zend_Form_Decorator');
        $this->assertTrue(is_array($paths));

        $loader = new Zend_Loader_PluginLoader;
        $this->element->setPluginLoader($loader, 'decorator');
        $test = $this->element->getPluginLoader('decorator');
        $this->assertSame($loader, $test);
    }

    public function testElementShouldProxyToParentWhenSettingDecoratorPrefixPaths()
    {
        $this->element->addPrefixPath('Foo_Decorator', 'Foo/Decorator/', 'decorator');
        $loader = $this->element->getPluginLoader('decorator');
        $paths = $loader->getPaths('Foo_Decorator');
        $this->assertTrue(is_array($paths));
    }

    public function testElementShouldAddToAllPluginLoadersWhenAddingNullPrefixPath()
    {
        $this->element->addPrefixPath('Foo', 'Foo');
        foreach (array('validate', 'filter', 'decorator', 'transfer_adapter') as $type) {
            $loader = $this->element->getPluginLoader($type);
            $string = str_replace('_', ' ', $type);
            $string = ucwords($string);
            $string = str_replace(' ', '_', $string);
            $prefix = 'Foo_' . $string;
            $paths  = $loader->getPaths($prefix);
            $this->assertTrue(is_array($paths), "Failed asserting paths found for prefix $prefix");
        }
    }

    public function testElementShouldUseHttpTransferAdapterByDefault()
    {
        $adapter = $this->element->getTransferAdapter();
        $this->assertTrue($adapter instanceof Zend_File_Transfer_Adapter_Http);
    }

    public function testElementShouldAllowSpecifyingAdapterUsingConcreteInstance()
    {
        $adapter = new Zend_Form_Element_FileTest_MockAdapter();
        $this->element->setTransferAdapter($adapter);
        $test = $this->element->getTransferAdapter();
        $this->assertSame($adapter, $test);
    }

    /**
     * @expectedException Zend_Form_Element_Exception
     */
    public function testElementShouldThrowExceptionWhenAddingAdapterOfInvalidType()
    {
        $this->element->setTransferAdapter(new stdClass);
    }

    public function testShouldRegisterPluginLoaderWithFileTransferAdapterPathByDefault()
    {
        $loader = $this->element->getPluginLoader('transfer_adapter');
        $this->assertTrue($loader instanceof Zend_Loader_PluginLoader_Interface);
        $paths = $loader->getPaths('Zend_File_Transfer_Adapter');
        $this->assertTrue(is_array($paths));
    }

    public function testElementShouldAllowSpecifyingAdapterUsingPluginLoader()
    {
        $this->element->addPrefixPath('Zend_Form_Element_FileTest_Adapter', dirname(__FILE__) . '/_files/TransferAdapter', 'transfer_adapter');
        $this->element->setTransferAdapter('Foo');
        $test = $this->element->getTransferAdapter();
        $this->assertTrue($test instanceof Zend_Form_Element_FileTest_Adapter_Foo);
    }

    public function testValidatorAccessAndMutationShouldProxyToAdapter()
    {
        $this->testElementShouldAllowSpecifyingAdapterUsingConcreteInstance();
        $this->element->addValidator('Count', false, 1)
                      ->addValidators(array(
                          'Extension' => 'jpg',
                          new Zend_Validate_File_Upload(),
                      ));
        $validators = $this->element->getValidators();
        $test       = $this->element->getTransferAdapter()->getValidators();
        $this->assertEquals($validators, $test);
        $this->assertTrue(is_array($test));
        $this->assertEquals(3, count($test));

        $validator = $this->element->getValidator('count');
        $test      = $this->element->getTransferAdapter()->getValidator('count');
        $this->assertNotNull($validator);
        $this->assertSame($validator, $test);

        $this->element->removeValidator('Extension');
        $this->assertFalse($this->element->getTransferAdapter()->hasValidator('Extension'));

        $this->element->setValidators(array(
            'Upload',
            array('validator' => 'Extension', 'options' => 'jpg'),
            array('validator' => 'Count', 'options' => 1),
        ));
        $validators = $this->element->getValidators();
        $test       = $this->element->getTransferAdapter()->getValidators();
        $this->assertSame($validators, $test);
        $this->assertTrue(is_array($test));
        $this->assertEquals(3, count($test), var_export($test, 1));

        $this->element->clearValidators();
        $validators = $this->element->getValidators();
        $this->assertTrue(is_array($validators));
        $this->assertEquals(0, count($validators));
        $test = $this->element->getTransferAdapter()->getValidators();
        $this->assertSame($validators, $test);
    }

    public function testValidationShouldProxyToAdapter()
    {
        $this->markTestIncomplete('Unsure how to accurately test');

        $this->element->setTransferAdapter(new Zend_Form_Element_FileTest_MockAdapter);
        $this->element->addValidator('Regex', '/([a-z0-9]{13})$/i');
        $this->assertTrue($this->element->isValid('foo.jpg'));
    }

    public function testDestinationMutatorsShouldProxyToTransferAdapter()
    {
        $this->element->setDestination(dirname(__FILE__));
        $this->assertEquals(dirname(__FILE__), $this->element->getDestination());
        $this->assertEquals(dirname(__FILE__), $this->element->getTransferAdapter()->getDestination('foo'));
    }

    public function testSettingMultipleFiles()
    {
        $this->element->setMultiFile(3);
        $this->assertEquals(3, $this->element->getMultiFile());
    }

    public function testFileInSubSubSubform()
    {
        $form = new Zend_Form();
        $element = new Zend_Form_Element_File('file1');
        $element2 = new Zend_Form_Element_File('file2');

        $subform0 = new Zend_Form_SubForm();
        $subform0->addElement($element);
        $subform0->addElement($element2);
        $subform1 = new Zend_Form_SubForm();
        $subform1->addSubform($subform0, 'subform0');
        $subform2 = new Zend_Form_SubForm();
        $subform2->addSubform($subform1, 'subform1');
        $subform3 = new Zend_Form_SubForm();
        $subform3->addSubform($subform2, 'subform2');
        $form->addSubform($subform3, 'subform3');

        $form->setView(new Zend_View());
        $output = (string) $form;
        $this->assertContains('name="file1"', $output);
        $this->assertContains('name="file2"', $output);
    }

    public function testMultiFileInSubSubSubform()
    {
        $form = new Zend_Form();
        $element = new Zend_Form_Element_File('file');
        $element->setMultiFile(2);

        $subform0 = new Zend_Form_SubForm();
        $subform0->addElement($element);
        $subform1 = new Zend_Form_SubForm();
        $subform1->addSubform($subform0, 'subform0');
        $subform2 = new Zend_Form_SubForm();
        $subform2->addSubform($subform1, 'subform1');
        $subform3 = new Zend_Form_SubForm();
        $subform3->addSubform($subform2, 'subform2');
        $form->addSubform($subform3, 'subform3');

        $form->setView(new Zend_View());
        $output = (string) $form;
        $this->assertContains('name="file[]"', $output);
        $this->assertEquals(2, substr_count($output, 'file[]'));
    }

    public function testSettingMaxFileSize()
    {
        $this->assertEquals(0, $this->element->getMaxFileSize());
        $this->element->setMaxFileSize(3000);
        $this->assertEquals(3000, $this->element->getMaxFileSize());
    }
}

class Zend_Form_Element_FileTest_MockAdapter extends Zend_File_Transfer_Adapter_Abstract
{
    public $received = false;

    public function __construct()
    {
        $this->_files = array(
            'foo' => array(
                'name'     => 'foo.jpg',
                'type'     => 'image/jpeg',
                'size'     => 126976,
                'tmp_name' => '/tmp/489127ba5c89c',
                'validators' => array(),
            ),
            'bar' => array(
                'name'     => 'bar.png',
                'type'     => 'image/png',
                'size'     => 91136,
                'tmp_name' => '/tmp/489128284b51f',
                'validators' => array(),
            ),
            'baz' => array(
                'name'     => 'baz.text',
                'type'     => 'text/plain',
                'size'     => 1172,
                'tmp_name' => '/tmp/4891286cceff3',
                'validators' => array(),
            ),
        );
    }

    public function send($options = null)
    {
        return;
    }

    public function receive($options = null)
    {
        $this->received = true;
        return;
    }

    public function isSent($file = null)
    {
        return false;
    }

    public function isReceived($file = null)
    {
        return $this->received;
    }

    public function getProgress()
    {
        return;
    }
}

// Call Zend_Form_Element_FileTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Form_Element_FileTest::main") {
    Zend_Form_Element_FileTest::main();
}
