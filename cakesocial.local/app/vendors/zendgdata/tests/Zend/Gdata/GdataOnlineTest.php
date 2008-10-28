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
 * @package    Zend_Gdata
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

require_once 'Zend/Http/Client.php';
require_once 'Zend/Gdata.php';
require_once 'Zend/Gdata/App/MediaEntry.php';
require_once 'Zend/Gdata/App/MediaFileSource.php';
require_once 'Zend/Gdata/ClientLogin.php';
require_once 'Zend/Gdata/App/InvalidArgumentException.php';

/**
 * @package Zend_Gdata
 * @subpackage UnitTests
 */
class Zend_Gdata_GdataOnlineTest extends PHPUnit_Framework_TestCase
{
    private $blog = null; // blog ID from config

    public function setUp()
    {
        $user = constant('TESTS_ZEND_GDATA_CLIENTLOGIN_EMAIL');
        $pass = constant('TESTS_ZEND_GDATA_CLIENTLOGIN_PASSWORD');
        $this->blog = constant('TESTS_ZEND_GDATA_BLOG_ID');
        $service = 'blogger';
        $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
        $this->gdata = new Zend_Gdata($client);
    }

    public function testPostAndDeleteByEntry()
    {
        $postUrl = 'http://www.blogger.com/feeds/' . $this->blog .
                '/posts/default';
        $entry = $this->gdata->newEntry();
        $entry->title = $this->gdata->newTitle('PHP test blog post');
        $entry->content = $this->gdata->newContent('Blog post content...');
        $insertedEntry = $this->gdata->insertEntry($entry, $postUrl);
        $this->assertEquals('PHP test blog post', $insertedEntry->title->text);
        $this->assertEquals('Blog post content...',
                $insertedEntry->content->text);
        $this->assertTrue( 
                strpos($insertedEntry->getEditLink()->href, 'http') === 0);
        $this->gdata->delete($insertedEntry);
    }

    public function testPostAndDeleteByUrl()
    {
        $postUrl = 'http://www.blogger.com/feeds/' . $this->blog .
                '/posts/default';
        $entry = $this->gdata->newEntry();
        $entry->title = $this->gdata->newTitle('PHP test blog post');
        $entry->content = $this->gdata->newContent('Blog post content...');
        $insertedEntry = $this->gdata->insertEntry($entry, $postUrl);
        $this->assertTrue( 
                strpos($insertedEntry->getEditLink()->href, 'http') === 0);
        $this->gdata->delete($insertedEntry->getEditLink()->href);
    }

    public function testPostRetrieveEntryAndDelete()
    {
        $postUrl = 'http://www.blogger.com/feeds/' . $this->blog .
                '/posts/default';
        $entry = $this->gdata->newEntry();
        $entry->title = $this->gdata->newTitle(' PHP test blog post ');
        $this->assertTrue(isset($entry->title));
        $entry->content = $this->gdata->newContent('Blog post content...');

        /* testing getText and __toString */
        $this->assertEquals("PHP test blog post",
                $entry->title->getText());
        $this->assertEquals(" PHP test blog post ",
                $entry->title->getText(false)); 
        $this->assertEquals($entry->title->getText(),
            $entry->title->__toString());

        $insertedEntry = $this->gdata->insertEntry($entry, $postUrl);
        $retrievedEntryQuery = $this->gdata->newQuery(
                $insertedEntry->getSelfLink()->href);
        $retrievedEntry = $this->gdata->getEntry($retrievedEntryQuery);
        $this->assertTrue( 
                strpos($retrievedEntry->getEditLink()->href, 'http') === 0);
        $this->gdata->delete($retrievedEntry);
    }

    public function testPostUpdateAndDeleteEntry() 
    {
        $postUrl = 'http://www.blogger.com/feeds/' . $this->blog .
                '/posts/default';
        $entry = $this->gdata->newEntry();
        $entry->title = $this->gdata->newTitle('PHP test blog post');
        $entry->content = $this->gdata->newContent('Blog post content...');
        $insertedEntry = $this->gdata->insertEntry($entry, $postUrl);
        $this->assertTrue( 
                strpos($insertedEntry->getEditLink()->href, 'http') === 0);
        $insertedEntry->title->text = 'PHP test blog post modified';
        $updatedEntry = $this->gdata->updateEntry($insertedEntry);
        $this->assertEquals('PHP test blog post modified',
                $updatedEntry->title->text);
        $updatedEntry->title->text = 'PHP test blog post modified twice';
        // entry->saveXML() and entry->getXML() should be the same
        $this->assertEquals($updatedEntry->saveXML(), 
                $updatedEntry->getXML());
        $newlyUpdatedEntry = $this->gdata->updateEntry($updatedEntry);
        $this->assertEquals('PHP test blog post modified twice',
                $updatedEntry->title->text);
        $updatedEntry->delete();
    }

    public function testFeedImplementation()
    {
        $blogsUrl = 'http://www.blogger.com/feeds/default/blogs';
        $blogsQuery = $this->gdata->newQuery($blogsUrl);
        $retrievedFeed = $this->gdata->getFeed($blogsQuery);

        // Make sure the iterator and array impls match
        $entry1 = $retrievedFeed->current();
        $entry2 = $retrievedFeed[0];
        $this->assertEquals($entry1, $entry2);

        /*
        TODO: Fix these tests
        // Test ArrayAccess interface
        $firstBlogTitle = $retrievedFeed[0]->title->text;
        $entries = $retrievedFeed->entry;
        $entries[0]->title->text = $firstBlogTitle . "**";
        $retrievedFeed[0] = $entries[0];
        $this->assertEquals($retrievedFeed->entry[0]->title->text,
                $retrievedFeed[0]->title->text);
        $this->assertEquals($firstBlogTitle . "**",
                $retrievedFeed[0]->title->text);
        */
    }

    public function testBadFeedRetrieval()
    {
        $feed = $this->gdata->newFeed();
        try {
            $returnedFeed = $this->gdata->getFeed($feed);
        } catch (Zend_Gdata_App_InvalidArgumentException $e) {
            // we're expecting to cause an exception here
        }
    }

    public function testBadEntryRetrieval()
    {
        $entry = $this->gdata->newEntry();
        try {
            $returnedEntry = $this->gdata->getEntry($entry);
        } catch (Zend_Gdata_App_InvalidArgumentException $e) {
            // we're expecting to cause an exception here
        }
    }

    public function testMediaUpload()
    {
        // the standard sevice for GData testing is Blogger, due to the strong
        // match to the standard GData/APP protocol.  However, Blogger doesn't
        // currently support media uploads, so we're using Picasa Web Albums
        // for this test instead
        $user = constant('TESTS_ZEND_GDATA_CLIENTLOGIN_EMAIL');
        $pass = constant('TESTS_ZEND_GDATA_CLIENTLOGIN_PASSWORD');
        $this->blog = constant('TESTS_ZEND_GDATA_BLOG_ID');
        $service = 'lh2';
        $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
        $gd = new Zend_Gdata($client);

        // setup the photo content
        $fs = $gd->newMediaFileSource('Zend/Gdata/_files/testImage.jpg');
        $fs->setContentType('image/jpeg');


        // create a new picasa album
        $albumEntry = $gd->newEntry();
        $albumEntry->setTitle($gd->newTitle('My New Test Album'));
        $albumEntry->setCategory(array($gd->newCategory(
                'http://schemas.google.com/photos/2007#album',
                'http://schemas.google.com/g/2005#kind'
                )));
        $createdAlbumEntry = $gd->insertEntry($albumEntry, 
                'http://picasaweb.google.com/data/feed/api/user/default');
        $this->assertEquals('My New Test Album', 
                $createdAlbumEntry->title->text);
        $albumUrl = $createdAlbumEntry->getLink('http://schemas.google.com/g/2005#feed')->href;
    
        // post the photo to the new album, without any metadata 
        // other than the slug
        // add a slug header to the media file source
        $fs->setSlug('Going to the park');
        $createdPhotoBinaryOnly = $gd->insertEntry($fs, $albumUrl);
        $this->assertEquals('Going to the park', 
                $createdPhotoBinaryOnly->title->text);

        // post the photo to the new album along with the entry 
        // remove slug header from the media file source
        $fs->setSlug(null);

        // setup an entry with metadata
        $mediaEntry = $gd->newMediaEntry();
        $mediaEntry->setMediaSource($fs);

        $mediaEntry->setTitle($gd->newTitle('My New Test Photo'));
        $mediaEntry->setSummary($gd->newSummary('My New Test Photo Summary'));
        $mediaEntry->setCategory(array($gd->newCategory(
                'http://schemas.google.com/photos/2007#photo ',
                'http://schemas.google.com/g/2005#kind'
                )));
        $createdPhotoMultipart = $gd->insertEntry($mediaEntry, $albumUrl);
        $this->assertEquals('My New Test Photo', 
                $createdPhotoMultipart->title->text);
        
    }

}
