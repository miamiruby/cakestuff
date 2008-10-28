<?php
// Call Zend_Form_Element_SubmitTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Form_Element_SubmitTest::main");
}

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'Zend/Form/Element/Submit.php';
require_once 'Zend/Translate.php';

/**
 * Test class for Zend_Form_Element_Submit
 */
class Zend_Form_Element_SubmitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Zend_Form_Element_SubmitTest");
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
        $this->element = new Zend_Form_Element_Submit('foo');
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

    public function getView()
    {
        require_once 'Zend/View.php';
        $view = new Zend_View();
        $view->addHelperPath(dirname(__FILE__) . '/../../../../library/Zend/View/Helper/');
        return $view;
    }

    public function testSubmitElementSubclassesXhtmlElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element_Xhtml);
    }

    public function testSubmitElementInstanceOfBaseElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element);
    }

    public function testSubmitElementUsesViewHelperDecoratorByDefault()
    {
        $this->_checkZf2794();

        $decorator = $this->element->getDecorator('viewHelper');
        $this->assertTrue($decorator instanceof Zend_Form_Decorator_ViewHelper);
    }

    public function testSubmitElementSpecifiesFormSubmitAsDefaultHelper()
    {
        $this->assertEquals('formSubmit', $this->element->helper);
    }

    public function testGetLabelReturnsNameIfNoValuePresent()
    {
        $this->assertEquals($this->element->getName(), $this->element->getLabel());
    }

    public function testGetLabelReturnsTranslatedLabelIfTranslatorIsRegistered()
    {
        $translations = include dirname(__FILE__) . '/../_files/locale/array.php';
        $translate = new Zend_Translate('array', $translations, 'en');
        $this->element->setTranslator($translate)
                      ->setLabel('submit');
        $test = $this->element->getLabel();
        $this->assertEquals($translations['submit'], $test);
    }

    public function testTranslatedLabelIsRendered()
    {
        $this->_checkZf2794();

        $this->testGetLabelReturnsTranslatedLabelIfTranslatorIsRegistered();
        $this->element->setView($this->getView());
        $decorator = $this->element->getDecorator('ViewHelper');
        $decorator->setElement($this->element);
        $html = $decorator->render('');
        $this->assertRegexp('/<(input|button)[^>]*?value="Submit Button"/', $html);
    }

    public function testConstructorSetsLabelToNameIfNoLabelProvided()
    {
        $submit = new Zend_Form_Element_Submit('foo');
        $this->assertEquals('foo', $submit->getName());
        $this->assertEquals('foo', $submit->getLabel());
    }

    public function testCanPassLabelAsParameterToConstructor()
    {
        $submit = new Zend_Form_Element_Submit('foo', 'Label');
        $this->assertEquals('Label', $submit->getLabel());
    }

    public function testLabelIsTranslatedWhenTranslationAvailable()
    {
        require_once 'Zend/Translate.php';
        $translations = array('Label' => 'This is the Submit Label');
        $translate = new Zend_Translate('array', $translations);
        $submit = new Zend_Form_Element_Submit('foo', 'Label');
        $submit->setTranslator($translate);
        $this->assertEquals($translations['Label'], $submit->getLabel());
    }

    public function testIsCheckedReturnsFalseWhenNoValuePresent()
    {
        $this->assertFalse($this->element->isChecked());
    }

    public function testIsCheckedReturnsFalseWhenValuePresentButDoesNotMatchLabel()
    {
        $this->assertFalse($this->element->isChecked());
        $this->element->setValue('bar');
        $this->assertFalse($this->element->isChecked());
    }

    public function testIsCheckedReturnsTrueWhenValuePresentAndMatchesLabel()
    {
        $this->testIsCheckedReturnsFalseWhenNoValuePresent();
        $this->element->setValue('foo');
        $this->assertTrue($this->element->isChecked());
    }

    /**
     * Used by test methods susceptible to ZF-2794, marks a test as incomplete
     *
     * @link   http://framework.zend.com/issues/browse/ZF-2794
     * @return void
     */
    protected function _checkZf2794()
    {
        if (strtolower(substr(PHP_OS, 0, 3)) == 'win' && version_compare(PHP_VERSION, '5.1.4', '=')) {
            $this->markTestIncomplete('Error occurs for PHP 5.1.4 on Windows');
        }
    }
}

// Call Zend_Form_Element_SubmitTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Form_Element_SubmitTest::main") {
    Zend_Form_Element_SubmitTest::main();
}
