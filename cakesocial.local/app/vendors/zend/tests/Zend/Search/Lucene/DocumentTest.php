<?php
/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage UnitTests
 */


/**
 * Zend_Search_Lucene_Document
 */
require_once 'Zend/Search/Lucene/Document.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage UnitTests
 */
class Zend_Search_Lucene_DocumentTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $document =  new Zend_Search_Lucene_Document();

        $this->assertEquals($document->boost, 1);
    }

    public function testFields()
    {
        $document =  new Zend_Search_Lucene_Document();

        $document->addField(Zend_Search_Lucene_Field::Text('title',      'Title'));
        $document->addField(Zend_Search_Lucene_Field::Text('annotation', 'Annotation'));
        $document->addField(Zend_Search_Lucene_Field::Text('body',       'Document body, document body, document body...'));

        $fieldnamesDiffArray = array_diff($document->getFieldNames(), array('title', 'annotation', 'body'));
        $this->assertTrue(is_array($fieldnamesDiffArray));
        $this->assertEquals(count($fieldnamesDiffArray), 0);

        $this->assertEquals($document->title,      'Title');
        $this->assertEquals($document->annotation, 'Annotation');
        $this->assertEquals($document->body,       'Document body, document body, document body...');

        $this->assertEquals($document->getField('title')->value,      'Title');
        $this->assertEquals($document->getField('annotation')->value, 'Annotation');
        $this->assertEquals($document->getField('body')->value,       'Document body, document body, document body...');

        $this->assertEquals($document->getFieldValue('title'),      'Title');
        $this->assertEquals($document->getFieldValue('annotation'), 'Annotation');
        $this->assertEquals($document->getFieldValue('body'),       'Document body, document body, document body...');


        $wordsWithUmlautsIso88591 = iconv('UTF-8', 'ISO-8859-1', 'Words with umlauts: åãü...');
        $document->addField(Zend_Search_Lucene_Field::Text('description', $wordsWithUmlautsIso88591, 'ISO-8859-1'));
        $this->assertEquals($document->description, $wordsWithUmlautsIso88591);
        $this->assertEquals($document->getFieldUtf8Value('description'), 'Words with umlauts: åãü...');
    }

    public function testAddFieldMethodChaining()
    {
        $document =  new Zend_Search_Lucene_Document();
        $this->assertTrue($document->addField(Zend_Search_Lucene_Field::Text('title', 'Title')) instanceof Zend_Search_Lucene_Document);

        $document =  new Zend_Search_Lucene_Document();
        $document->addField(Zend_Search_Lucene_Field::Text('title',      'Title'))
                 ->addField(Zend_Search_Lucene_Field::Text('annotation', 'Annotation'))
                 ->addField(Zend_Search_Lucene_Field::Text('body',       'Document body, document body, document body...'));
    }

    public function testHtml()
    {
        $doc =  Zend_Search_Lucene_Document_Html::loadHTML('<HTML><HEAD><TITLE>Page title</TITLE></HEAD><BODY>Document body.</BODY></HTML>');
        $this->assertTrue($doc instanceof Zend_Search_Lucene_Document_Html);

        $doc->highlight('document', '#66ffff');
        $this->assertTrue(strpos($doc->getHTML(), "<b style=\"color:black;background-color:#66ffff\">Document</b> body.") !== false);

        $doc =  Zend_Search_Lucene_Document_Html::loadHTMLFile(dirname(__FILE__) . '/_indexSource/_files/contributing.documentation.html', true);
        $this->assertTrue($doc instanceof Zend_Search_Lucene_Document_Html);

        $this->assertTrue(array_values($doc->getHeaderLinks()) ==
                          array('index.html', 'contributing.html', 'contributing.bugs.html', 'contributing.wishlist.html'));
        $this->assertTrue(array_values($doc->getLinks()) ==
                          array('contributing.bugs.html',
                                'contributing.wishlist.html',
                                'developers.documentation.html',
                                'faq.translators-revision-tracking.html',
                                'index.html',
                                'contributing.html'));
    }

    public function testHtmlNoFollowLinks()
    {
    	$html = '<HTML>'
                . '<HEAD><TITLE>Page title</TITLE></HEAD>'
                . '<BODY>'
                .   'Document body.'
                .   '<a href="link1.html">Link 1</a>.'
                .   '<a href="link2.html" rel="nofollow">Link 1</a>.'
                . '</BODY>'
              . '</HTML>';

        $oldNoFollowValue = Zend_Search_Lucene_Document_Html::getExcludeNoFollowLinks();

        Zend_Search_Lucene_Document_Html::setExcludeNoFollowLinks(false);
        $doc1 = Zend_Search_Lucene_Document_Html::loadHTML($html);
        $this->assertTrue($doc1 instanceof Zend_Search_Lucene_Document_Html);
        $this->assertTrue(array_values($doc1->getLinks()) == array('link1.html', 'link2.html'));

        Zend_Search_Lucene_Document_Html::setExcludeNoFollowLinks(true);
        $doc2 = Zend_Search_Lucene_Document_Html::loadHTML($html);
        $this->assertTrue($doc2 instanceof Zend_Search_Lucene_Document_Html);
        $this->assertTrue(array_values($doc2->getLinks()) == array('link1.html'));
    }
}

