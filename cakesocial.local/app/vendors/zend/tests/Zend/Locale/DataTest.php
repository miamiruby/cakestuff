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
 * @package    Zend_Locale
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DataTest.php 8025 2008-02-15 08:53:01Z thomas $
 */

/**
 * Zend_Locale_Data
 */
require_once 'Zend/Locale/Data.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';


/**
 * @package    Zend_Locale
 * @subpackage UnitTests
 */
class Zend_Locale_DataTest extends PHPUnit_Framework_TestCase
{

    private $_cache = null;

    public function setUp()
    {
        require_once 'Zend/Cache.php';
        $this->_cache = Zend_Cache::factory('Core', 'File',
                 array('lifetime' => 1, 'automatic_serialization' => true),
                 array('cache_dir' => dirname(__FILE__) . '/../_files/'));
        Zend_Locale_Data::setCache($this->_cache);
    }


    public function tearDown()
    {
        $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
    }

    /**
     * test for reading with standard locale
     * expected array
     */
    public function testNoLocale()
    {
        $this->assertTrue(is_array(Zend_Locale_Data::getList(null, 'language')));

        try {
            $value = Zend_Locale_Data::getList('nolocale','language');
            $this->fail('locale should throw exception');
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        $locale = new Zend_Locale('de');
        $this->assertTrue(is_array(Zend_Locale_Data::getList($locale, 'language')));
    }


    /**
     * test for reading without type
     * expected empty array
     */
    public function testNoType()
    {
        try {
            $value = Zend_Locale_Data::getContent('de','');
            $this->fail('content should throw an exception');
        } catch (Zend_Locale_Exception $e) {
            // success
        }

        try {
            $value = Zend_Locale_Data::getContent('de','xxxxxxx');
            $this->fail('content should throw an exception');
        } catch (Zend_Locale_Exception $e) {
            // success
        }
    }


    /**
     * test for reading the languagelist from locale
     * expected array
     */
    public function testLanguage()
    {
        $data = Zend_Locale_Data::getList('de','language');
        $this->assertEquals('Deutsch',  $data['de']);
        $this->assertEquals('Englisch', $data['en']);

        $value = Zend_Locale_Data::getContent('de', 'language', 'de');
        $this->assertEquals('Deutsch', $value);
    }

    /**
     * test for reading the scriptlist from locale
     * expected array
     */
    public function testScript()
    {
        $data = Zend_Locale_Data::getList('de_AT', 'script');
        $this->assertEquals('Arabisch',   $data['Arab']);
        $this->assertEquals('Lateinisch', $data['Latn']);

        $value = Zend_Locale_Data::getContent('de_AT', 'script', 'Arab');
        $this->assertEquals('Arabisch', $value);
    }

    /**
     * test for reading the territorylist from locale
     * expected array
     */
    public function testTerritory()
    {
        $data = Zend_Locale_Data::getList('de_AT', 'territory');
        $this->assertEquals('Österreich', $data['AT']);
        $this->assertEquals('Martinique', $data['MQ']);

        $value = Zend_Locale_Data::getContent('de_AT', 'territory', 'AT');
        $this->assertEquals('Österreich', $value);
    }

    /**
     * test for reading the variantlist from locale
     * expected array
     */
    public function testVariant()
    {
        $data = Zend_Locale_Data::getList('de_AT', 'variant');
        $this->assertEquals('Boontling', $data['BOONT']);
        $this->assertEquals('Saho',      $data['SAAHO']);

        $value = Zend_Locale_Data::getContent('de_AT', 'variant', 'POSIX');
        $this->assertEquals('Posix', $value);
    }

    /**
     * test for reading the keylist from locale
     * expected array
     */
    public function testKey()
    {
        $data = Zend_Locale_Data::getList('de_AT', 'key');
        $this->assertEquals('Kalender',   $data['calendar']);
        $this->assertEquals('Sortierung', $data['collation']);

        $value = Zend_Locale_Data::getContent('de_AT', 'key', 'collation');
        $this->assertEquals('Sortierung', $value);
    }

    /**
     * test for reading the typelist from locale
     * expected array
     */
    public function testType()
    {
        $data = Zend_Locale_Data::getList('de_AT', 'type');
        $this->assertEquals('Chinesischer Kalender', $data['chinese']);
        $this->assertEquals('Strichfolge',           $data['stroke']);

        $data = Zend_Locale_Data::getList('de_AT', 'type', 'calendar');
        $this->assertEquals('Chinesischer Kalender', $data['chinese']);
        $this->assertEquals('Japanischer Kalender',  $data['japanese']);

        $value = Zend_Locale_Data::getList('de_AT', 'type', 'chinese');
        $this->assertEquals('Chinesischer Kalender', $value['chinese']);
    }

    /**
     * test for reading layout from locale
     * expected array
     */
    public function testLayout()
    {
        $layout = Zend_Locale_Data::getList('es', 'layout');
        $this->assertEquals("", $layout['lines']);
        $this->assertEquals("", $layout['characters']);
        $this->assertEquals("titlecase-firstword", $layout['inList']);
        $this->assertEquals("lowercase-words",     $layout['currency']);
        $this->assertEquals("mixed",               $layout['dayWidth']);
        $this->assertEquals("mixed",               $layout['fields']);
        $this->assertEquals("lowercase-words",     $layout['keys']);
        $this->assertEquals("lowercase-words",     $layout['languages']);
        $this->assertEquals("lowercase-words",     $layout['long']);
        $this->assertEquals("lowercase-words",     $layout['measurementSystemNames']);
        $this->assertEquals("mixed",               $layout['monthWidth']);
        $this->assertEquals("mixed",               $layout['quarterWidth']);
        $this->assertEquals("mixed",               $layout['scripts']);
        $this->assertEquals("mixed",               $layout['territories']);
        $this->assertEquals("lowercase-words",     $layout['types']);
        $this->assertEquals("mixed",               $layout['variants']);
    }

    /**
     * test for reading characters from locale
     * expected array
     */
    public function testCharacters()
    {
        $char = Zend_Locale_Data::getList('de', 'characters');
        $this->assertEquals("[a ä b-o ö p-s ß t u ü v-z]", $char['characters']);
        $this->assertEquals("[á à ă â å ä ā æ ç é è ĕ ê ë ē í ì ĭ î ï ī ñ ó ò ŏ ô ö ø ō œ ß ú ù ŭ û ü ū ÿ]", $char['auxiliary']);
        $this->assertEquals("[\\$ £ ¥ ₤ ₧ € a-z]", $char['currencySymbol']);
    }

    /**
     * test for reading delimiters from locale
     * expected array
     */
    public function testDelimiters()
    {
        $quote = Zend_Locale_Data::getList('de', 'delimiters');
        $this->assertEquals("„", $quote['quoteStart']);
        $this->assertEquals("“", $quote['quoteEnd']);
        $this->assertEquals("‚", $quote['quoteStartAlt']);
        $this->assertEquals("‘", $quote['quoteEndAlt']);
    }

    /**
     * test for reading measurement from locale
     * expected array
     */
    public function testMeasurement()
    {
        $measure = Zend_Locale_Data::getList('de', 'measurement');
        $this->assertEquals("001", $measure['metric']);
        $this->assertEquals("US",  $measure['US']);
        $this->assertEquals("001", $measure['A4']);
        $this->assertEquals("US",  $measure['US-Letter']);
    }

    /**
     * test for reading datechars from locale
     * expected array
     */
    public function testDateChars()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'datechars');
        $this->assertEquals("GjMtkHmsSEDFwWahKzJeugAZvcL", $date);
    }

    /**
     * test for reading defaultcalendar from locale
     * expected array
     */
    public function testDefaultCalendar()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'defaultcalendar');
        $this->assertEquals("gregorian", $date);
    }

    /**
     * test for reading defaultmonthcontext from locale
     * expected array
     */
    public function testDefaultMonthContext()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'monthcontext');
        $this->assertEquals("format", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'monthcontext', 'islamic');
        $this->assertEquals("format", $date);
    }

    /**
     * test for reading defaultmonth from locale
     * expected array
     */
    public function testDefaultMonth()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'defaultmonth');
        $this->assertEquals("wide", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'defaultmonth', 'islamic');
        $this->assertEquals("wide", $date);
    }

    /**
     * test for reading month from locale
     * expected array
     */
    public function testMonth()
    {
        $date   = Zend_Locale_Data::getList('de_AT', 'months');
        $result = array("context" => "format", "default" => "wide", "format" =>
            array("abbreviated" =>
                array(1 => "Jän",  2 => "Feb", 3 => "Mär", 4 => "Apr", 5 => "Mai",
                      6 => "Jun",  7 => "Jul", 8 => "Aug", 9 => "Sep", 10=> "Okt",
                     11 => "Nov", 12 => "Dez"),
                  "narrow" => array(1 => '1', 2 => '2',  3 => '3',   4 =>  '4', 5 =>   '5', 6 => '6', 7 => '7',
                                    8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12'),
                  "wide" =>
                array(1 => "Jänner"  , 2 => "Februar"   , 3 => "März"  , 4 => "April"    , 5 => "Mai",
                      6 => "Juni"    , 7 => "Juli"      , 8 => "August", 9 => "September", 10=> "Oktober",
                     11 => "November", 12 => "Dezember")
            ),
            "stand-alone" => array("abbreviated" =>
                array(1 => '1',    2 =>     '2',  3 =>    '3',  4 =>    '4',  5 =>    '5', 6 => '6', 7 => "Jul.",
                      8 => "Aug.", 9 => "Sept.", 10 => "Okt.", 11 => "Nov.", 12 => "Dez."),
                  "narrow" =>
                array(1 => "J",  2 => "F",  3 => "M",  4 => "A", 5 => "M", 6 => "J",  7 => "J" , 8 => "A",
                      9 => "S", 10 => "O", 11 => "N", 12 => "D"),
                  "wide" => array(1 => '1', 2 => '2',  3 => '3',   4 =>  '4', 5 =>   '5', 6 => '6', 7 => '7',
                                  8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12'),
            ));
        $this->assertEquals($result, $date);

        $date   = Zend_Locale_Data::getList('de_AT', 'months', 'islamic');
        $result = array("context" => "format", "default" => "wide", "format" =>
            array("abbreviated" =>
                array(1 => "Muharram"  , 2 => "Safar", 3 => "Rabiʻ I"  , 4 => "Rabiʻ II"    , 5 => "Jumada I",
                      6 => "Jumada II" , 7 => "Rajab", 8 => "Shaʻban", 9 => "Ramadan", 10=> "Shawwal",
                     11 => "Dhuʻl-Qiʻdah", 12 => "Dhuʻl-Hijjah"),
                  "narrow" => array(1 => '1', 2 => '2',  3 => '3',   4 =>  '4', 5 =>   '5', 6 => '6', 7 => '7',
                                    8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12'),
                  "wide" =>
                array(1 => "Muharram"  , 2 => "Safar", 3 => "Rabiʻ I"  , 4 => "Rabiʻ II"    , 5 => "Jumada I",
                      6 => "Jumada II" , 7 => "Rajab", 8 => "Shaʻban", 9 => "Ramadan", 10=> "Shawwal",
                     11 => "Dhuʻl-Qiʻdah", 12 => "Dhuʻl-Hijjah")
            ),
            "stand-alone" => array("abbreviated" =>
                array(1 => "Muharram"  , 2 => "Safar", 3 => "Rabiʻ I"  , 4 => "Rabiʻ II"    , 5 => "Jumada I",
                      6 => "Jumada II" , 7 => "Rajab", 8 => "Shaʻban", 9 => "Ramadan", 10=> "Shawwal",
                     11 => "Dhuʻl-Qiʻdah", 12 => "Dhuʻl-Hijjah"),
                  "narrow" => array(1 => '1', 2 => '2',  3 => '3',   4 =>  '4', 5 =>   '5', 6 => '6', 7 => '7',
                                  8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12'),
                  "wide" =>
                array(1 => "Muharram"  , 2 => "Safar", 3 => "Rabiʻ I"  , 4 => "Rabiʻ II"    , 5 => "Jumada I",
                      6 => "Jumada II" , 7 => "Rajab", 8 => "Shaʻban", 9 => "Ramadan", 10=> "Shawwal",
                     11 => "Dhuʻl-Qiʻdah", 12 => "Dhuʻl-Hijjah")
            ));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'month');
        $this->assertEquals(array(1 => "Jänner"  , 2 => "Februar"   , 3 => "März"  , 4 => "April"    , 5 => "Mai",
                                  6 => "Juni"    , 7 => "Juli"      , 8 => "August", 9 => "September", 10=> "Oktober",
                                 11 => "November", 12 => "Dezember"), $date);

        $date = Zend_Locale_Data::getList('de_AT', 'month', array('gregorian', 'format', 'wide'));
        $this->assertEquals(array(1 => "Jänner"  , 2 => "Februar"   , 3 => "März"  , 4 => "April"    , 5 => "Mai",
                                  6 => "Juni"    , 7 => "Juli"      , 8 => "August", 9 => "September", 10=> "Oktober",
                                 11 => "November", 12 => "Dezember"), $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'month', 12);
        $this->assertEquals('Dezember', $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'month', array('gregorian', 'format', 'wide', 12));
        $this->assertEquals('Dezember', $value);

        $value = Zend_Locale_Data::getContent('ar', 'month', array('islamic', 'format', 'wide', 1));
        $this->assertEquals("محرم", $value);
    }

    /**
     * test for reading defaultdaycontext from locale
     * expected array
     */
    public function testDefaultDayContext()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'daycontext');
        $this->assertEquals("format", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'daycontext', 'islamic');
        $this->assertEquals("format", $date);
    }

    /**
     * test for reading defaultday from locale
     * expected array
     */
    public function testDefaultDay()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'defaultday');
        $this->assertEquals("wide", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'defaultday', 'islamic');
        $this->assertEquals("wide", $date);
    }

    /**
     * test for reading day from locale
     * expected array
     */
    public function testDay()
    {
        $date = Zend_Locale_Data::getList('de_AT', 'days');
        $result = array("context" => "format", "default" => "wide", "format" =>
            array("abbreviated" => array("sun" => "So", "mon" => "Mo", "tue" => "Di", "wed" => "Mi",
                      "thu" => "Do", "fri" => "Fr", "sat" => "Sa"),
                  "narrow" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "wide" => array("sun" => "Sonntag", "mon" => "Montag", "tue" => "Dienstag",
                      "wed" => "Mittwoch", "thu" => "Donnerstag", "fri" => "Freitag", "sat" => "Samstag")
            ),
            "stand-alone" => array("abbreviated" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "narrow" => array("sun" => "S", "mon" => "M", "tue" => "D", "wed" => "M",
                      "thu" => "D", "fri" => "F", "sat" => "S"),
                  "wide" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7")
            ));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'days', 'islamic');
        $result = array("context" => "format", "default" => "wide", "format" =>
            array("abbreviated" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "narrow" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "wide" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7")
            ),
            "stand-alone" => array("abbreviated" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "narrow" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7"),
                  "wide" => array("sun" => "1", "mon" => "2", "tue" => "3", "wed" => "4",
                      "thu" => "5", "fri" => "6", "sat" => "7")
            ));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'day');
        $this->assertEquals(array("sun" => "Sonntag", "mon" => "Montag", "tue" => "Dienstag",
                      "wed" => "Mittwoch", "thu" => "Donnerstag", "fri" => "Freitag", "sat" => "Samstag"), $date);

        $date = Zend_Locale_Data::getList('de_AT', 'day', array('gregorian', 'format', 'wide'));
        $this->assertEquals(array("sun" => "Sonntag", "mon" => "Montag", "tue" => "Dienstag",
                      "wed" => "Mittwoch", "thu" => "Donnerstag", "fri" => "Freitag", "sat" => "Samstag"), $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'day', 'mon');
        $this->assertEquals('Montag', $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'day', array('gregorian', 'format', 'wide', 'mon'));
        $this->assertEquals('Montag', $value);

        $value = Zend_Locale_Data::getContent('ar', 'day', array('islamic', 'format', 'wide', 'mon'));
        $this->assertEquals("2", $value);
    }

    /**
     * test for reading quarter from locale
     * expected array
     */
    public function testQuarter()
    {
        $date = Zend_Locale_Data::getList('de_AT', 'quarters');
        $result = array("format" =>
            array("abbreviated" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4"),
                  "narrow" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4"),
                  "wide" => array("1" => "1. Quartal", "2" => "2. Quartal", "3" => "3. Quartal",
                      "4" => "4. Quartal")
            ),
            "stand-alone" => array("abbreviated" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4"),
                  "narrow" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4"),
                  "wide" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4")
            ));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'quarters', 'islamic');
        $result = array("format" =>
            array("abbreviated" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4"),
                  "narrow" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4"),
                  "wide" => array("1" => "Q1", "2" => "Q2", "3" => "Q3",
                      "4" => "Q4")
            ),
            "stand-alone" => array("abbreviated" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4"),
                  "narrow" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4"),
                  "wide" => array("1" => "Q1", "2" => "Q2", "3" => "Q3", "4" => "Q4")
            ));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'quarter');
        $this->assertEquals(array("1" => "1. Quartal", "2" => "2. Quartal", "3" => "3. Quartal",
                      "4" => "4. Quartal"), $date);

        $date = Zend_Locale_Data::getList('de_AT', 'quarter', array('gregorian', 'format', 'wide'));
        $this->assertEquals(array("1" => "1. Quartal", "2" => "2. Quartal", "3" => "3. Quartal",
                      "4" => "4. Quartal"), $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'quarter', '1');
        $this->assertEquals('1. Quartal', $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'quarter', array('gregorian', 'format', 'wide', '1'));
        $this->assertEquals('1. Quartal', $value);

        $value = Zend_Locale_Data::getContent('ar', 'quarter', array('islamic', 'format', 'wide', '1'));
        $this->assertEquals("Q1", $value);
    }

    /**
     * test for reading week from locale
     * expected array
     */
    public function testWeek()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'week');
        $this->assertEquals(array('minDays' => 4, 'firstDay' => 'mon', 'weekendStart' => 'sat',
                                  'weekendEnd' => 'sun'), $value);

        $value = Zend_Locale_Data::getList('en_US', 'week');
        $this->assertEquals(array('minDays' => 1, 'firstDay' => 'sun', 'weekendStart' => 'sat',
                                  'weekendEnd' => 'sun'), $value);
    }

    /**
     * test for reading am from locale
     * expected array
     */
    public function testAm()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'am');
        $this->assertEquals("vorm.", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'am', 'islamic');
        $this->assertEquals("AM", $date);
    }

    /**
     * test for reading pm from locale
     * expected array
     */
    public function testPm()
    {
        $date = Zend_Locale_Data::getContent('de_AT', 'pm');
        $this->assertEquals("nachm.", $date);

        $date = Zend_Locale_Data::getContent('de_AT', 'pm', 'islamic');
        $this->assertEquals("PM", $date);
    }

    /**
     * test for reading era from locale
     * expected array
     */
    public function testEra()
    {
        $date = Zend_Locale_Data::getList('de_AT', 'eras');
        $result = array(
            "abbreviated" => array("0" => "v. Chr.", "1" => "n. Chr."),
            "narrow" => array("0" => "BCE", "1" => "CE"),
            "names" => array("0" => "v. Chr.", "1" => "n. Chr.")
            );
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'eras', 'islamic');
        $result = array("abbreviated" => array("0" => "AH"), "narrow" => array("0" => "AH"), "names" => array("0" => "AH"));
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'era');
        $this->assertEquals(array("0" => "v. Chr.", "1" => "n. Chr."), $date);

        $date = Zend_Locale_Data::getList('de_AT', 'era', array('gregorian', 'Abbr'));
        $this->assertEquals(array("0" => "v. Chr.", "1" => "n. Chr."), $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'era', '1');
        $this->assertEquals('n. Chr.', $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'era', array('gregorian', 'Names', '1'));
        $this->assertEquals('n. Chr.', $value);

        $value = Zend_Locale_Data::getContent('ar', 'era', array('islamic', 'Abbr', '0'));
        $this->assertEquals("ه‍", $value);
    }

    /**
     * test for reading defaultdate from locale
     * expected array
     */
    public function testDefaultDate()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'defaultdate');
        $this->assertEquals("medium", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'defaultdate', 'gregorian');
        $this->assertEquals("medium", $value);
    }

    /**
     * test for reading era from locale
     * expected array
     */
    public function testDate()
    {
        $date = Zend_Locale_Data::getList('de_AT', 'date');
        $result = array("full" => "EEEE, dd. MMMM yyyy", "long" => "dd. MMMM yyyy",
                        "medium" => "dd.MM.yyyy", "short" => "dd.MM.yy");
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'date', 'islamic');
        $result = array("full" => "EEEE, yyyy MMMM dd", "long" => "yyyy MMMM d",
                        "medium" => "yyyy MMM d", "short" => "yyyy-MM-dd");
        $this->assertEquals($result, $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'date');
        $this->assertEquals("dd.MM.yyyy", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'date', 'long');
        $this->assertEquals("dd. MMMM yyyy", $value);

        $value = Zend_Locale_Data::getContent('ar', 'date', array('islamic', 'long'));
        $this->assertEquals("yyyy MMMM d", $value);
    }

    /**
     * test for reading defaulttime from locale
     * expected array
     */
    public function testDefaultTime()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'defaulttime');
        $this->assertEquals("medium", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'defaulttime', 'gregorian');
        $this->assertEquals("medium", $value);
    }

    /**
     * test for reading time from locale
     * expected array
     */
    public function testTime()
    {
        $date = Zend_Locale_Data::getList('de_AT', 'time');
        $result = array("full" => "HH:mm:ss v", "long" => "HH:mm:ss z",
                        "medium" => "HH:mm:ss", "short" => "HH:mm");
        $this->assertEquals($result, $date);

        $date = Zend_Locale_Data::getList('de_AT', 'time', 'islamic');
        $result = array("full" => "HH:mm:ss v", "long" => "HH:mm:ss z",
                        "medium" => "HH:mm:ss", "short" => "HH:mm");
        $this->assertEquals($result, $date);

        $value = Zend_Locale_Data::getContent('de_AT', 'time');
        $this->assertEquals("HH:mm:ss", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'time', 'long');
        $this->assertEquals("HH:mm:ss z", $value);

        $value = Zend_Locale_Data::getContent('ar', 'time', array('islamic', 'long'));
        $this->assertEquals("HH:mm:ss z", $value);
    }

    /**
     * test for reading datetime from locale
     * expected array
     */
    public function testDateTime()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'datetime');
        $result = array(
            "Ed"    => "E d",     "H"      => "H",       "HHmm"   => "HH:mm",    "HHmmss"   => "HH:mm:ss",
            "MMMEd" => "E MMM d", "MMMMd"  => "d. MMMM", "MMMMdd" => "dd. MMMM", "MMd"      => "d/MM",
            "MMdd"  => "dd.MM.",  "Md"     => "d.M.",    "hhmm"   => "hh:mm a",  "hhmmss"   => "hh:mm:ss a",
            "mmss"  => "mm:ss",   "yyMM"   => "MM.yy",   "yyMMM"  => "MMM yy",   "yyMMdd"   => "dd.MM.yy",
            "yyQ"   => "Q yy",    "yyQQQQ" => "QQQQ yy", "yyyy"   => "yyyy",     "yyyyMMMM" => "MMMM yyyy");
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getList('de_AT', 'datetime', 'gregorian');
        $result = array(
            "Ed"    => "E d",     "H"      => "H",       "HHmm"   => "HH:mm",    "HHmmss"   => "HH:mm:ss",
            "MMMEd" => "E MMM d", "MMMMd"  => "d. MMMM", "MMMMdd" => "dd. MMMM", "MMd"      => "d/MM",
            "MMdd"  => "dd.MM.",  "Md"     => "d.M.",    "hhmm"   => "hh:mm a",  "hhmmss"   => "hh:mm:ss a",
            "mmss"  => "mm:ss",   "yyMM"   => "MM.yy",   "yyMMM"  => "MMM yy",   "yyMMdd"   => "dd.MM.yy",
            "yyQ"   => "Q yy",    "yyQQQQ" => "QQQQ yy", "yyyy"   => "yyyy",     "yyyyMMMM" => "MMMM yyyy");
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'datetime');
        $this->assertEquals("{1} {0}", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'datetime', 'gregorian');
        $this->assertEquals("{1} {0}", $value);
    }

    /**
     * test for reading field from locale
     * expected array
     */
    public function testField()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'field');
        $this->assertEquals(array("era" => "Epoche", "year" => "Jahr", "month" => "Monat", "week" => "Woche",
            "day" => "Tag", "weekday" => "Wochentag", "dayperiod" => "Tageshälfte", "hour" => "Stunde",
            "minute" => "Minute", "second" => "Sekunde", "zone" => "Zone"), $value);

        $value = Zend_Locale_Data::getList('de_AT', 'field', 'gregorian');
        $this->assertEquals(array("era" => "Epoche", "year" => "Jahr", "month" => "Monat", "week" => "Woche",
            "day" => "Tag", "weekday" => "Wochentag", "dayperiod" => "Tageshälfte", "hour" => "Stunde",
            "minute" => "Minute", "second" => "Sekunde", "zone" => "Zone"), $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'field', 'week');
        $this->assertEquals("Woche", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'field', array('gregorian', 'week'));
        $this->assertEquals("Woche", $value);
    }

    /**
     * test for reading relative from locale
     * expected array
     */
    public function testRelative()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'relative');
        $this->assertEquals(array("0" => "Heute", "1" => "Morgen", "2" => "Übermorgen",
            "3" => "in drei Tagen", "-1" => "Gestern", "-2" => "Vorgestern"), $value);

        $value = Zend_Locale_Data::getList('de_AT', 'relative', 'gregorian');
        $this->assertEquals(array("0" => "Heute", "1" => "Morgen", "2" => "Übermorgen",
            "3" => "in drei Tagen", "-1" => "Gestern", "-2" => "Vorgestern"), $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'relative', '-1');
        $this->assertEquals("Gestern", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'relative', array('gregorian', '-1'));
        $this->assertEquals("Gestern", $value);
    }

    /**
     * test for reading symbols from locale
     * expected array
     */
    public function testSymbols()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'symbols');
        $result = array(    "decimal"  => ",", "group" => ".", "list"  => ";", "percent"  => "%",
            "zero"  => "0", "pattern"  => "#", "plus"  => "+", "minus" => "-", "exponent" => "E",
            "mille" => "‰", "infinity" => "∞", "nan"   => "NaN");
        $this->assertEquals($result, $value);
    }

    /**
     * test for reading decimalnumber from locale
     * expected array
     */
    public function testDecimalNumber()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'decimalnumber');
        $this->assertEquals("#,##0.###", $value);
    }

    /**
     * test for reading scientificnumber from locale
     * expected array
     */
    public function testScientificNumber()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'scientificnumber');
        $this->assertEquals("#E0", $value);
    }

    /**
     * test for reading percentnumber from locale
     * expected array
     */
    public function testPercentNumber()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'percentnumber');
        $this->assertEquals("#,##0 %", $value);
    }

    /**
     * test for reading currencynumber from locale
     * expected array
     */
    public function testCurrencyNumber()
    {
        $value = Zend_Locale_Data::getContent('de_AT', 'currencynumber');
        $this->assertEquals("¤ #,##0.00", $value);
    }

    /**
     * test for reading nametocurrency from locale
     * expected array
     */
    public function testNameToCurrency()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'nametocurrency');
        $result = array(
            'ADP' => 'Andorranische Pesete', 'AED' => 'UAE Dirham', 'AFA' => 'Afghani (1927-2002)',
            'AFN' => 'Afghani', 'ALL' => 'Lek', 'AMD' => 'Dram', 'ANG' => 'Niederl. Antillen Gulden',
            'AOA' => 'Kwanza', 'AOK' => 'Angolanischer Kwanza (1977-1990)', 'AON' => 'Neuer Kwanza',
            'AOR' => 'Kwanza Reajustado', 'ARA' => 'Argentinischer Austral',
            'ARP' => 'Argentinischer Peso (1983-1985)', 'ARS' => 'Argentinischer Peso',
            'ATS' => 'Österreichischer Schilling', 'AUD' => 'Australischer Dollar', 'AWG' => 'Aruba Florin',
            'AZM' => 'Aserbeidschan Manat', 'AZN' => 'Aserbaidschan-Manat',
            'BAD' => 'Bosnien und Herzegowina Dinar', 'BAM' => 'Konvertierbare Mark',
            'BBD' => 'Barbados-Dollar', 'BDT' => 'Taka', 'BEC' => 'Belgischer Franc (konvertibel)',
            'BEF' => 'Belgischer Franc', 'BEL' => 'Belgischer Finanz-Franc', 'BGL' => 'Lew (1962-1999)',
            'BGN' => 'Lew', 'BHD' => 'Bahrain-Dinar', 'BIF' => 'Burundi-Franc', 'BMD' => 'Bermuda-Dollar',
            'BND' => 'Brunei-Dollar', 'BOB' => 'Boliviano', 'BOP' => 'Bolivianischer Peso', 'BOV' => 'Mvdol',
            'BRB' => 'Brasilianischer Cruzeiro Novo (1967-1986)', 'BRC' => 'Brasilianischer Cruzado',
            'BRE' => 'Brasilianischer Cruzeiro (1990-1993)', 'BRL' => 'Real',
            'BRN' => 'Brasilianischer Cruzado Novo', 'BRR' => 'Brasilianischer Cruzeiro',
            'BSD' => 'Bahama-Dollar', 'BTN' => 'Ngultrum', 'BUK' => 'Birmanischer Kyat', 'BWP' => 'Pula',
            'BYB' => 'Belarus Rubel (alt)', 'BYR' => 'Belarus Rubel (neu)', 'BZD' => 'Belize-Dollar',
            'CAD' => 'Kanadischer Dollar', 'CDF' => 'Franc congolais', 'CHE' => 'WIR Euro',
            'CHF' => 'Schweizer Franken', 'CHW' => 'WIR Franken', 'CLF' => 'Unidades de Fomento',
            'CLP' => 'Chilenischer Peso', 'CNY' => 'Renminbi Yuan', 'COP' => 'Kolumbianischer Peso',
            'COU' => 'Unidad de Valor Real', 'CRC' => 'Costa Rica Colon', 'CSD' => 'Alter Serbischer Dinar',
            'CSK' => 'Tschechoslowakische Krone', 'CUP' => 'Kubanischer Peso', 'CVE' => 'Kap Verde Escudo',
            'CYP' => 'Zypern Pfund', 'CZK' => 'Tschechische Krone', 'DDM' => 'Mark der DDR',
            'DEM' => 'Deutsche Mark', 'DJF' => 'Dschibuti-Franc', 'DKK' => 'Dänische Krone',
            'DOP' => 'Dominikanischer Peso', 'DZD' => 'Algerischer Dinar', 'ECS' => 'Ecuadorianischer Sucre',
            'ECV' => 'Verrechnungseinheit für EC', 'EEK' => 'Estnische Krone', 'EGP' => 'Ägyptisches Pfund',
            'EQE' => 'Ekwele', 'ERN' => 'Nakfa', 'ESA' => 'Spanische Peseta (A-Konten)',
            'ESB' => 'Spanische Peseta (konvertibel)', 'ESP' => 'Spanische Pesete', 'ETB' => 'Birr',
            'EUR' => 'Euro', 'FIM' => 'Finnische Mark', 'FJD' => 'Fidschi Dollar', 'FKP' => 'Falkland Pfund',
            'FRF' => 'Französischer Franc', 'GBP' => 'Pfund Sterling', 'GEK' => 'Georgischer Kupon Larit',
            'GEL' => 'Georgischer Lari', 'GHC' => 'Cedi', 'GIP' => 'Gibraltar Pfund', 'GMD' => 'Dalasi',
            'GNF' => 'Guinea Franc', 'GNS' => 'Guineischer Syli', 'GQE' => 'Äquatorialguinea Ekwele Guineana',
            'GRD' => 'Griechische Drachme', 'GTQ' => 'Quetzal', 'GWE' => 'Portugiesisch Guinea Escudo',
            'GWP' => 'Guinea Bissau Peso', 'GYD' => 'Guyana Dollar', 'HKD' => 'Hongkong-Dollar',
            'HNL' => 'Lempira', 'HRD' => 'Kroatischer Dinar', 'HRK' => 'Kuna', 'HTG' => 'Gourde',
            'HUF' => 'Forint', 'IDR' => 'Rupiah', 'IEP' => 'Irisches Pfund', 'ILP' => 'Israelisches Pfund',
            'ILS' => 'Schekel', 'INR' => 'Indische Rupie', 'IQD' => 'Irak Dinar', 'IRR' => 'Rial',
            'ISK' => 'Isländische Krone', 'ITL' => 'Italienische Lire', 'JMD' => 'Jamaika Dollar',
            'JOD' => 'Jordanischer Dinar', 'JPY' => 'Yen', 'KES' => 'Kenia Schilling', 'KGS' => 'Som',
            'KHR' => 'Riel', 'KMF' => 'Komoren Franc', 'KPW' => 'Nordkoreanischer Won',
            'KRW' => 'Südkoreanischer Won', 'KWD' => 'Kuwait Dinar', 'KYD' => 'Kaiman-Dollar',
            'KZT' => 'Tenge', 'LAK' => 'Kip', 'LBP' => 'Libanesisches Pfund', 'LKR' => 'Sri Lanka Rupie',
            'LRD' => 'Liberianischer Dollar', 'LSL' => 'Loti', 'LSM' => 'Maloti', 'LTL' => 'Litauischer Litas',
            'LTT' => 'Litauischer Talonas', 'LUC' => 'Luxemburgischer Franc (konvertibel)',
            'LUF' => 'Luxemburgischer Franc', 'LUL' => 'Luxemburgischer Finanz-Franc',
            'LVL' => 'Lettischer Lats', 'LVR' => 'Lettischer Rubel', 'LYD' => 'Libyscher Dinar',
            'MAD' => 'Marokkanischer Dirham', 'MAF' => 'Marokkanischer Franc', 'MDL' => 'Moldau Leu',
            'MGA' => 'Madagaskar Ariary', 'MGF' => 'Madagaskar Franc', 'MKD' => 'Denar',
            'MLF' => 'Malischer Franc', 'MMK' => 'Kyat', 'MNT' => 'Tugrik', 'MOP' => 'Pataca',
            'MRO' => 'Ouguiya', 'MTL' => 'Maltesische Lira', 'MTP' => 'Maltesisches Pfund',
            'MUR' => 'Mauritius Rupie', 'MVR' => 'Rufiyaa', 'MWK' => 'Malawi Kwacha',
            'MXN' => 'Mexikanischer Peso', 'MXP' => 'Mexikanischer Silber-Peso (1861-1992)',
            'MXV' => 'Mexican Unidad de Inversion (UDI)', 'MYR' => 'Malaysischer Ringgit',
            'MZE' => 'Mosambikanischer Escudo', 'MZM' => 'Alter Metical', 'MZN' => 'Metical',
            'NAD' => 'Namibia Dollar', 'NGN' => 'Naira', 'NIC' => 'Cordoba', 'NIO' => 'Gold-Cordoba',
            'NLG' => 'Holländischer Gulden', 'NOK' => 'Norwegische Krone', 'NPR' => 'Nepalesische Rupie',
            'NZD' => 'Neuseeland-Dollar', 'OMR' => 'Rial Omani', 'PAB' => 'Balboa',
            'PEI' => 'Peruanischer Inti', 'PEN' => 'Neuer Sol', 'PES' => 'Sol', 'PGK' => 'Kina',
            'PHP' => 'Philippinischer Peso', 'PKR' => 'Pakistanische Rupie', 'PLN' => 'Zloty',
            'PLZ' => 'Zloty (1950-1995)', 'PTE' => 'Portugiesischer Escudo', 'PYG' => 'Guarani',
            'QAR' => 'Katar Riyal', 'RHD' => 'Rhodesischer Dollar', 'ROL' => 'Leu', 'RON' => 'Rumänischer Leu',
            'RSD' => 'Serbischer Dinar', 'RUB' => 'Russischer Rubel (neu)', 'RUR' => 'Russischer Rubel (alt)',
            'RWF' => 'Ruanda Franc', 'SAR' => 'Saudi Riyal', 'SBD' => 'Salomonen Dollar',
            'SCR' => 'Seychellen Rupie', 'SDD' => 'Sudanesischer Dinar', 'SDP' => 'Sudanesisches Pfund',
            'SEK' => 'Schwedische Krone', 'SGD' => 'Singapur-Dollar', 'SHP' => 'St. Helena Pfund',
            'SIT' => 'Tolar', 'SKK' => 'Slowakische Krone', 'SLL' => 'Leone', 'SOS' => 'Somalia Schilling',
            'SRD' => 'Surinamischer Dollar', 'SRG' => 'Suriname Gulden', 'STD' => 'Dobra',
            'SUR' => 'Sowjetischer Rubel', 'SVC' => 'El Salvador Colon', 'SYP' => 'Syrisches Pfund',
            'SZL' => 'Lilangeni', 'THB' => 'Baht', 'TJR' => 'Tadschikistan Rubel',
            'TJS' => 'Tadschikistan Somoni', 'TMM' => 'Turkmenistan-Manat', 'TND' => 'Tunesischer Dinar',
            'TOP' => 'Paʻanga', 'TPE' => 'Timor Escudo', 'TRL' => 'Türkische Lira',
            'TRY' => 'Neue Türkische Lira', 'TTD' => 'Trinidad und Tobago Dollar',
            'TWD' => 'Neuer Taiwan Dollar', 'TZS' => 'Tansania Schilling', 'UAH' => 'Hryvnia',
            'UAK' => 'Ukrainischer Karbovanetz', 'UGS' => 'Uganda Schilling (1966-1987)',
            'UGX' => 'Uganda Schilling', 'USD' => 'US-Dollar', 'USN' => 'US Dollar (Nächster Tag)',
            'USS' => 'US Dollar (Gleicher Tag)', 'UYP' => 'Uruguayischer Neuer Peso (1975-1993)',
            'UYU' => 'Uruguayischer Peso', 'UZS' => 'Usbekistan Sum', 'VEB' => 'Bolivar', 'VND' => 'Dong',
            'VUV' => 'Vatu', 'WST' => 'Tala', 'XAF' => 'CFA Franc (Äquatorial)', 'XAG' => 'Silber',
            'XAU' => 'Gold', 'XBA' => 'Europäische Rechnungseinheit',
            'XBB' => 'Europäische Währungseinheit (XBB)', 'XBC' => 'Europäische Rechnungseinheit (XBC)',
            'XBD' => 'Europäische Rechnungseinheit (XBD)', 'XCD' => 'Ostkaribischer Dollar',
            'XDR' => 'Sonderziehungsrechte', 'XEU' => 'Europäische Währungseinheit (XEU)',
            'XFO' => 'Französischer Gold-Franc', 'XFU' => 'Französischer UIC-Franc',
            'XOF' => 'CFA Franc (West)', 'XPD' => 'Palladium', 'XPF' => 'CFP Franc', 'XPT' => 'Platin',
            'XRE' => 'RINET Funds', 'XTS' => 'Testwährung', 'XXX' => 'Keine Währung', 'YDD' => 'Jemen Dinar',
            'YER' => 'Jemen Rial', 'YUD' => 'Jugoslawischer Dinar (1966-1990)', 'YUM' => 'Neuer Dinar',
            'YUN' => 'Jugoslawischer Dinar (konvertibel)', 'ZAR' => 'Rand', 'ZMK' => 'Kwacha',
            'ZRN' => 'Neuer Zaire', 'ZRZ' => 'Zaire', 'ZWD' => 'Simbabwe Dollar');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'nametocurrency', 'USD');
        $this->assertEquals("US-Dollar", $value);
    }

    /**
     * test for reading currencytoname from locale
     * expected array
     */
    public function testCurrencyToName()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'currencytoname');
        $result = array('Andorranische Pesete' => 'ADP', 'UAE Dirham' => 'AED', 'Afghani (1927-2002)' => 'AFA',
            'Afghani' => 'AFN', 'Lek' => 'ALL', 'Dram' => 'AMD', 'Niederl. Antillen Gulden' => 'ANG',
            'Kwanza' => 'AOA', 'Angolanischer Kwanza (1977-1990)' => 'AOK', 'Neuer Kwanza' => 'AON',
            'Kwanza Reajustado' => 'AOR', 'Argentinischer Austral' => 'ARA', 'Argentinischer Peso (1983-1985)' => 'ARP',
            'Argentinischer Peso' => 'ARS', 'Österreichischer Schilling' => 'ATS', 'Australischer Dollar' => 'AUD',
            'Aruba Florin' => 'AWG', 'Aserbeidschan Manat' => 'AZM', 'Aserbaidschan-Manat' => 'AZN',
            'Bosnien und Herzegowina Dinar' => 'BAD', 'Konvertierbare Mark' => 'BAM', 'Barbados-Dollar' => 'BBD',
            'Taka' => 'BDT', 'Belgischer Franc (konvertibel)' => 'BEC', 'Belgischer Franc' => 'BEF',
            'Belgischer Finanz-Franc' => 'BEL', 'Lew (1962-1999)' => 'BGL', 'Lew' => 'BGN', 'Bahrain-Dinar' => 'BHD',
            'Burundi-Franc' => 'BIF', 'Bermuda-Dollar' => 'BMD', 'Brunei-Dollar' => 'BND', 'Boliviano' => 'BOB',
            'Bolivianischer Peso' => 'BOP', 'Mvdol' => 'BOV', 'Brasilianischer Cruzeiro Novo (1967-1986)' => 'BRB',
            'Brasilianischer Cruzado' => 'BRC', 'Brasilianischer Cruzeiro (1990-1993)' => 'BRE', 'Real' => 'BRL',
            'Brasilianischer Cruzado Novo' => 'BRN', 'Brasilianischer Cruzeiro' => 'BRR', 'Bahama-Dollar' => 'BSD',
            'Ngultrum' => 'BTN', 'Birmanischer Kyat' => 'BUK', 'Pula' => 'BWP', 'Belarus Rubel (alt)' => 'BYB',
            'Belarus Rubel (neu)' => 'BYR', 'Belize-Dollar' => 'BZD', 'Kanadischer Dollar' => 'CAD', 'Franc congolais' => 'CDF',
            'WIR Euro' => 'CHE', 'Schweizer Franken' => 'CHF', 'WIR Franken' => 'CHW', 'Unidades de Fomento' => 'CLF',
            'Chilenischer Peso' => 'CLP', 'Renminbi Yuan' => 'CNY', 'Kolumbianischer Peso' => 'COP', 'Unidad de Valor Real' => 'COU',
            'Costa Rica Colon' => 'CRC', 'Alter Serbischer Dinar' => 'CSD', 'Tschechoslowakische Krone' => 'CSK',
            'Kubanischer Peso' => 'CUP', 'Kap Verde Escudo' => 'CVE', 'Zypern Pfund' => 'CYP', 'Tschechische Krone' => 'CZK',
            'Mark der DDR' => 'DDM', 'Deutsche Mark' => 'DEM', 'Dschibuti-Franc' => 'DJF', 'Dänische Krone' => 'DKK',
            'Dominikanischer Peso' => 'DOP', 'Algerischer Dinar' => 'DZD', 'Ecuadorianischer Sucre' => 'ECS',
            'Verrechnungseinheit für EC' => 'ECV', 'Estnische Krone' => 'EEK', 'Ägyptisches Pfund' => 'EGP',
            'Ekwele' => 'EQE', 'Nakfa' => 'ERN', 'Spanische Peseta (A-Konten)' => 'ESA', 'Spanische Peseta (konvertibel)' => 'ESB',
            'Spanische Pesete' => 'ESP', 'Birr' => 'ETB', 'Euro' => 'EUR', 'Finnische Mark' => 'FIM',
            'Fidschi Dollar' => 'FJD', 'Falkland Pfund' => 'FKP', 'Französischer Franc' => 'FRF', 'Pfund Sterling' => 'GBP',
            'Georgischer Kupon Larit' => 'GEK', 'Georgischer Lari' => 'GEL', 'Cedi' => 'GHC', 'Gibraltar Pfund' => 'GIP',
            'Dalasi' => 'GMD', 'Guinea Franc' => 'GNF', 'Guineischer Syli' => 'GNS', 'Äquatorialguinea Ekwele Guineana' => 'GQE',
            'Griechische Drachme' => 'GRD', 'Quetzal' => 'GTQ', 'Portugiesisch Guinea Escudo' => 'GWE',
            'Guinea Bissau Peso' => 'GWP', 'Guyana Dollar' => 'GYD', 'Hongkong-Dollar' => 'HKD', 'Lempira' => 'HNL',
            'Kroatischer Dinar' => 'HRD', 'Kuna' => 'HRK', 'Gourde' => 'HTG', 'Forint' => 'HUF', 'Rupiah' => 'IDR',
            'Irisches Pfund' => 'IEP', 'Israelisches Pfund' => 'ILP', 'Schekel' => 'ILS', 'Indische Rupie' => 'INR',
            'Irak Dinar' => 'IQD', 'Rial' => 'IRR', 'Isländische Krone' => 'ISK', 'Italienische Lire' => 'ITL',
            'Jamaika Dollar' => 'JMD', 'Jordanischer Dinar' => 'JOD', 'Yen' => 'JPY', 'Kenia Schilling' => 'KES',
            'Som' => 'KGS', 'Riel' => 'KHR', 'Komoren Franc' => 'KMF', 'Nordkoreanischer Won' => 'KPW',
            'Südkoreanischer Won' => 'KRW', 'Kuwait Dinar' => 'KWD', 'Kaiman-Dollar' => 'KYD', 'Tenge' => 'KZT',
            'Kip' => 'LAK', 'Libanesisches Pfund' => 'LBP', 'Sri Lanka Rupie' => 'LKR', 'Liberianischer Dollar' => 'LRD',
            'Loti' => 'LSL', 'Maloti' => 'LSM', 'Litauischer Litas' => 'LTL', 'Litauischer Talonas' => 'LTT',
            'Luxemburgischer Franc (konvertibel)' => 'LUC', 'Luxemburgischer Franc' => 'LUF', 'Luxemburgischer Finanz-Franc' => 'LUL',
            'Lettischer Lats' => 'LVL', 'Lettischer Rubel' => 'LVR', 'Libyscher Dinar' => 'LYD', 'Marokkanischer Dirham' => 'MAD',
            'Marokkanischer Franc' => 'MAF', 'Moldau Leu' => 'MDL', 'Madagaskar Ariary' => 'MGA', 'Madagaskar Franc' => 'MGF',
            'Denar' => 'MKD', 'Malischer Franc' => 'MLF', 'Kyat' => 'MMK', 'Tugrik' => 'MNT', 'Pataca' => 'MOP',
            'Ouguiya' => 'MRO', 'Maltesische Lira' => 'MTL', 'Maltesisches Pfund' => 'MTP', 'Mauritius Rupie' => 'MUR',
            'Rufiyaa' => 'MVR', 'Malawi Kwacha' => 'MWK', 'Mexikanischer Peso' => 'MXN', 'Mexikanischer Silber-Peso (1861-1992)' => 'MXP',
            'Mexican Unidad de Inversion (UDI)' => 'MXV', 'Malaysischer Ringgit' => 'MYR', 'Mosambikanischer Escudo' => 'MZE',
            'Alter Metical' => 'MZM', 'Metical' => 'MZN', 'Namibia Dollar' => 'NAD', 'Naira' => 'NGN', 'Cordoba' => 'NIC',
            'Gold-Cordoba' => 'NIO', 'Holländischer Gulden' => 'NLG', 'Norwegische Krone' => 'NOK', 'Nepalesische Rupie' => 'NPR',
            'Neuseeland-Dollar' => 'NZD', 'Rial Omani' => 'OMR', 'Balboa' => 'PAB', 'Peruanischer Inti' => 'PEI',
            'Neuer Sol' => 'PEN', 'Sol' => 'PES', 'Kina' => 'PGK', 'Philippinischer Peso' => 'PHP', 'Pakistanische Rupie' => 'PKR',
            'Zloty' => 'PLN', 'Zloty (1950-1995)' => 'PLZ', 'Portugiesischer Escudo' => 'PTE', 'Guarani' => 'PYG',
            'Katar Riyal' => 'QAR', 'Rhodesischer Dollar' => 'RHD', 'Leu' => 'ROL', 'Rumänischer Leu' => 'RON',
            'Serbischer Dinar' => 'RSD', 'Russischer Rubel (neu)' => 'RUB', 'Russischer Rubel (alt)' => 'RUR',
            'Ruanda Franc' => 'RWF', 'Saudi Riyal' => 'SAR', 'Salomonen Dollar' => 'SBD', 'Seychellen Rupie' => 'SCR',
            'Sudanesischer Dinar' => 'SDD', 'Sudanesisches Pfund' => 'SDP', 'Schwedische Krone' => 'SEK',
            'Singapur-Dollar' => 'SGD', 'St. Helena Pfund' => 'SHP', 'Tolar' => 'SIT', 'Slowakische Krone' => 'SKK',
            'Leone' => 'SLL', 'Somalia Schilling' => 'SOS', 'Surinamischer Dollar' => 'SRD', 'Suriname Gulden' => 'SRG',
            'Dobra' => 'STD', 'Sowjetischer Rubel' => 'SUR', 'El Salvador Colon' => 'SVC', 'Syrisches Pfund' => 'SYP',
            'Lilangeni' => 'SZL', 'Baht' => 'THB', 'Tadschikistan Rubel' => 'TJR', 'Tadschikistan Somoni' => 'TJS',
            'Turkmenistan-Manat' => 'TMM', 'Tunesischer Dinar' => 'TND', 'Paʻanga' => 'TOP', 'Timor Escudo' => 'TPE',
            'Türkische Lira' => 'TRL', 'Neue Türkische Lira' => 'TRY', 'Trinidad und Tobago Dollar' => 'TTD',
            'Neuer Taiwan Dollar' => 'TWD', 'Tansania Schilling' => 'TZS', 'Hryvnia' => 'UAH', 'Ukrainischer Karbovanetz' => 'UAK',
            'Uganda Schilling (1966-1987)' => 'UGS', 'Uganda Schilling' => 'UGX', 'US-Dollar' => 'USD',
            'US Dollar (Nächster Tag)' => 'USN', 'US Dollar (Gleicher Tag)' => 'USS', 'Uruguayischer Neuer Peso (1975-1993)' => 'UYP',
            'Uruguayischer Peso' => 'UYU', 'Usbekistan Sum' => 'UZS', 'Bolivar' => 'VEB', 'Dong' => 'VND', 'Vatu' => 'VUV',
            'Tala' => 'WST', 'CFA Franc (Äquatorial)' => 'XAF', 'Silber' => 'XAG', 'Gold' => 'XAU',
            'Europäische Rechnungseinheit' => 'XBA', 'Europäische Währungseinheit (XBB)' => 'XBB',
            'Europäische Rechnungseinheit (XBC)' => 'XBC', 'Europäische Rechnungseinheit (XBD)' => 'XBD',
            'Ostkaribischer Dollar' => 'XCD', 'Sonderziehungsrechte' => 'XDR', 'Europäische Währungseinheit (XEU)' => 'XEU',
            'Französischer Gold-Franc' => 'XFO', 'Französischer UIC-Franc' => 'XFU', 'CFA Franc (West)' => 'XOF',
            'Palladium' => 'XPD', 'CFP Franc' => 'XPF', 'Platin' => 'XPT', 'RINET Funds' => 'XRE',
            'Testwährung' => 'XTS', 'Keine Währung' => 'XXX', 'Jemen Dinar' => 'YDD', 'Jemen Rial' => 'YER',
            'Jugoslawischer Dinar (1966-1990)' => 'YUD', 'Neuer Dinar' => 'YUM', 'Jugoslawischer Dinar (konvertibel)' => 'YUN',
            'Rand' => 'ZAR', 'Kwacha' => 'ZMK', 'Neuer Zaire' => 'ZRN', 'Zaire' => 'ZRZ', 'Simbabwe Dollar' => 'ZWD');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencytoname', 'Platin');
        $this->assertEquals("XPT", $value);
    }

    /**
     * test for reading currencysymbol from locale
     * expected array
     */
    public function testCurrencySymbol()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'currencysymbol');
        $result = array("AFN" => "Af", "ATS" => "öS", "BRL" => "R$", "CHF" => "SFr.", "DEM" => "DM",
            "ESP" => "₧", "EUR" => "€", "FRF" => "FF", "GBP" => "£", "INR" => "0≤Rs.|1≤Re.|1<Rs.",
            "ITL" => "₤", "JPY" => "¥", "KGS" => "som", "USD" => "$", "XCD" => "EC$");
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencysymbol', 'USD');
        $this->assertEquals("$", $value);
    }

    /**
     * test for reading question from locale
     * expected array
     */
    public function testQuestion()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'question');
        $this->assertEquals(array("yes" => "ja:j", "no" => "nein:n"), $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'question', 'yes');
        $this->assertEquals("ja:j", $value);
    }

    /**
     * test for reading currencyfraction from locale
     * expected array
     */
    public function testCurrencyFraction()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'currencyfraction');
        $this->assertEquals(array('ADP' => '0', 'BHD' => '3', 'BIF' => '0', 'BYR' => '0', 'CHF' => '2',
            'CLF' => '0', 'CLP' => '0', 'DEFAULT' => '2', 'DJF' => '0', 'ESP' => '0', 'GNF' => '0',
            'IQD' => '3', 'ITL' => '0', 'JOD' => '3', 'JPY' => '0', 'KMF' => '0', 'KRW' => '0', 'KWD' => '3',
            'LUF' => '0', 'LYD' => '3', 'MGA' => '0', 'MGF' => '0', 'OMR' => '3', 'PYG' => '0', 'RWF' => '0',
            'TND' => '3', 'TRL' => '0', 'VUV' => '0', 'XAF' => '0', 'XOF' => '0', 'XPF' => '0'), $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencyfraction');
        $this->assertEquals("2", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencyfraction', 'BHD');
        $this->assertEquals("3", $value);
    }

    /**
     * test for reading currencyrounding from locale
     * expected array
     */
    public function testCurrencyRounding()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'currencyrounding');
        $this->assertEquals(array('ADP' => '0', 'BHD' => '0', 'BIF' => '0', 'BYR' => '0', 'CHF' => '5',
            'CLF' => '0', 'CLP' => '0', 'DEFAULT' => '0', 'DJF' => '0', 'ESP' => '0', 'GNF' => '0',
            'IQD' => '0', 'ITL' => '0', 'JOD' => '0', 'JPY' => '0', 'KMF' => '0', 'KRW' => '0', 'KWD' => '0',
            'LUF' => '0', 'LYD' => '0', 'MGA' => '0', 'MGF' => '0', 'OMR' => '0', 'PYG' => '0', 'RWF' => '0',
            'TND' => '0', 'TRL' => '0', 'VUV' => '0', 'XAF' => '0', 'XOF' => '0', 'XPF' => '0'), $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencyrounding');
        $this->assertEquals("0", $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencyrounding', 'BHD');
        $this->assertEquals("0", $value);
    }

    /**
     * test for reading currencytoregion from locale
     * expected array
     */
    public function testCurrencyToRegion()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'currencytoregion');
        $result = array(   'AD' => 'EUR', 'AE' => 'AED', 'AF' => 'AFN', 'AG' => 'XCD', 'AI' => 'XCD',
            'AL' => 'ALL', 'AM' => 'AMD', 'AN' => 'ANG', 'AO' => 'AOA', 'AQ' => 'XXX', 'AR' => 'ARS',
            'AS' => 'USD', 'AT' => 'EUR', 'AU' => 'AUD', 'AW' => 'AWG', 'AX' => 'EUR', 'AZ' => 'AZN',
            'BA' => 'BAM', 'BB' => 'BBD', 'BD' => 'BDT', 'BE' => 'EUR', 'BF' => 'XOF', 'BG' => 'BGN',
            'BH' => 'BHD', 'BI' => 'BIF', 'BJ' => 'XOF', 'BM' => 'BMD', 'BN' => 'BND', 'BO' => 'BOB',
            'BR' => 'BRL', 'BS' => 'BSD', 'BT' => 'INR', 'BV' => 'NOK', 'BW' => 'BWP', 'BY' => 'BYR',
            'BZ' => 'BZD', 'CA' => 'CAD', 'CC' => 'AUD', 'CD' => 'CDF', 'CF' => 'XAF', 'CG' => 'XAF',
            'CH' => 'CHF', 'CI' => 'XOF', 'CK' => 'NZD', 'CL' => 'CLP', 'CM' => 'XAF', 'CN' => 'CNY',
            'CO' => 'COP', 'CR' => 'CRC', 'CS' => 'CSD', 'CU' => 'CUP', 'CV' => 'CVE', 'CX' => 'AUD',
            'CY' => 'EUR', 'CZ' => 'CZK', 'DE' => 'EUR', 'DJ' => 'DJF', 'DK' => 'DKK', 'DM' => 'XCD',
            'DO' => 'DOP', 'DZ' => 'DZD', 'EC' => 'USD', 'EE' => 'EEK', 'EG' => 'EGP', 'EH' => 'MAD',
            'ER' => 'ERN', 'ES' => 'EUR', 'ET' => 'ETB', 'FI' => 'EUR', 'FJ' => 'FJD', 'FK' => 'FKP',
            'FM' => 'USD', 'FO' => 'DKK', 'FR' => 'EUR', 'GA' => 'XAF', 'GB' => 'GBP', 'GD' => 'XCD',
            'GE' => 'GEL', 'GF' => 'EUR', 'GG' => 'GBP', 'GH' => 'GHS', 'GI' => 'GIP', 'GL' => 'DKK',
            'GM' => 'GMD', 'GN' => 'GNF', 'GP' => 'EUR', 'GQ' => 'XAF', 'GR' => 'EUR', 'GS' => 'GBP',
            'GT' => 'GTQ', 'GU' => 'USD', 'GW' => 'GWP', 'GY' => 'GYD', 'HK' => 'HKD', 'HM' => 'AUD',
            'HN' => 'HNL', 'HR' => 'HRK', 'HT' => 'HTG', 'HU' => 'HUF', 'ID' => 'IDR', 'IE' => 'EUR',
            'IL' => 'ILS', 'IM' => 'GBP', 'IN' => 'INR', 'IO' => 'USD', 'IQ' => 'IQD', 'IR' => 'IRR',
            'IS' => 'ISK', 'IT' => 'EUR', 'JE' => 'GBP', 'JM' => 'JMD', 'JO' => 'JOD', 'JP' => 'JPY',
            'KE' => 'KES', 'KG' => 'KGS', 'KH' => 'KHR', 'KI' => 'AUD', 'KM' => 'KMF', 'KN' => 'XCD',
            'KP' => 'KPW', 'KR' => 'KRW', 'KW' => 'KWD', 'KY' => 'KYD', 'KZ' => 'KZT', 'LA' => 'LAK',
            'LB' => 'LBP', 'LC' => 'XCD', 'LI' => 'CHF', 'LK' => 'LKR', 'LR' => 'LRD', 'LS' => 'ZAR',
            'LT' => 'LTL', 'LU' => 'EUR', 'LV' => 'LVL', 'LY' => 'LYD', 'MA' => 'MAD', 'MC' => 'EUR',
            'MD' => 'MDL', 'ME' => 'EUR', 'MG' => 'MGA', 'MH' => 'USD', 'MK' => 'MKD', 'ML' => 'XOF',
            'MM' => 'MMK', 'MN' => 'MNT', 'MO' => 'MOP', 'MP' => 'USD', 'MQ' => 'EUR', 'MR' => 'MRO',
            'MS' => 'XCD', 'MT' => 'EUR', 'MU' => 'MUR', 'MV' => 'MVR', 'MW' => 'MWK', 'MX' => 'MXN',
            'MY' => 'MYR', 'MZ' => 'MZN', 'NA' => 'ZAR', 'NC' => 'XPF', 'NE' => 'XOF', 'NF' => 'AUD',
            'NG' => 'NGN', 'NI' => 'NIO', 'NL' => 'EUR', 'NO' => 'NOK', 'NP' => 'NPR', 'NR' => 'AUD',
            'NU' => 'NZD', 'NZ' => 'NZD', 'OM' => 'OMR', 'PA' => 'PAB', 'PE' => 'PEN', 'PF' => 'XPF',
            'PG' => 'PGK', 'PH' => 'PHP', 'PK' => 'PKR', 'PL' => 'PLN', 'PM' => 'EUR', 'PN' => 'NZD',
            'PR' => 'USD', 'PS' => 'JOD', 'PT' => 'EUR', 'PW' => 'USD', 'PY' => 'PYG', 'QA' => 'QAR',
            'RE' => 'EUR', 'RO' => 'RON', 'RS' => 'RSD', 'RU' => 'RUB', 'RW' => 'RWF', 'SA' => 'SAR',
            'SB' => 'SBD', 'SC' => 'SCR', 'SD' => 'SDG', 'SE' => 'SEK', 'SG' => 'SGD', 'SH' => 'SHP',
            'SI' => 'EUR', 'SJ' => 'NOK', 'SK' => 'SKK', 'SL' => 'SLL', 'SM' => 'EUR', 'SN' => 'XOF',
            'SO' => 'SOS', 'SR' => 'SRD', 'ST' => 'STD', 'SV' => 'SVC', 'SY' => 'SYP', 'SZ' => 'SZL',
            'TC' => 'USD', 'TD' => 'XAF', 'TF' => 'EUR', 'TG' => 'XOF', 'TH' => 'THB', 'TJ' => 'TJS',
            'TK' => 'NZD', 'TL' => 'USD', 'TM' => 'TMM', 'TN' => 'TND', 'TO' => 'TOP', 'TR' => 'TRY',
            'TT' => 'TTD', 'TV' => 'AUD', 'TW' => 'TWD', 'TZ' => 'TZS', 'UA' => 'UAH', 'UG' => 'UGX',
            'UM' => 'USD', 'US' => 'USD', 'UY' => 'UYU', 'UZ' => 'UZS', 'VA' => 'EUR', 'VC' => 'XCD',
            'VE' => 'VEF', 'VG' => 'USD', 'VI' => 'USD', 'VN' => 'VND', 'VU' => 'VUV', 'WF' => 'XPF',
            'WS' => 'WST', 'YE' => 'YER', 'YT' => 'EUR', 'ZA' => 'ZAR', 'ZM' => 'ZMK', 'ZW' => 'ZWD',
            'ZR' => 'ZRN', 'YU' => 'YUM', 'TP' => 'TPE', 'SU' => 'SUR', 'QU' => 'EUR', 'MF' => 'EUR',
            'DD' => 'DDM', 'BU' => 'BUK', 'BL' => 'EUR');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'currencytoregion', 'AT');
        $this->assertEquals("EUR", $value);
    }

    /**
     * test for reading regiontocurrency from locale
     * expected array
     */
    public function testRegionToCurrency()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'regiontocurrency');
        $result = array(
            'EUR' => 'AD AT AX BE BL CY DE ES FI FR GF GP GR IE IT LU MC ME MF MQ MT NL PM PT QU RE SI SM TF VA YT',
            'AED' => 'AE', 'AFN' => 'AF', 'XCD' => 'AG AI DM GD KN LC MS VC', 'ALL' => 'AL', 'AMD' => 'AM',
            'ANG' => 'AN', 'AOA' => 'AO', 'XXX' => 'AQ', 'ARS' => 'AR', 'AWG' => 'AW', 'AZN' => 'AZ',
            'USD' => 'AS EC FM GU IO MH MP PR PW TC TL UM US VG VI', 'AUD' => 'AU CC CX HM KI NF NR TV',
            'BAM' => 'BA', 'BBD' => 'BB', 'BDT' => 'BD', 'XOF' => 'BF BJ CI ML NE SN TG', 'BGN' => 'BG',
            'BHD' => 'BH', 'BIF' => 'BI', 'BMD' => 'BM', 'BND' => 'BN', 'BOB' => 'BO', 'BRL' => 'BR',
            'BSD' => 'BS', 'INR' => 'BT IN', 'NOK' => 'BV NO SJ', 'BWP' => 'BW', 'BYR' => 'BY', 'BZD' => 'BZ',
            'CAD' => 'CA', 'CDF' => 'CD', 'XAF' => 'CF CG CM GA GQ TD', 'CHF' => 'CH LI',
            'NZD' => 'CK NU NZ PN TK', 'CLP' => 'CL', 'CNY' => 'CN', 'COP' => 'CO', 'CRC' => 'CR',
            'CUP' => 'CU', 'CVE' => 'CV', 'CZK' => 'CZ', 'DJF' => 'DJ', 'DKK' => 'DK FO GL', 'DOP' => 'DO',
            'DZD' => 'DZ', 'EEK' => 'EE', 'EGP' => 'EG', 'MAD' => 'EH MA', 'ERN' => 'ER', 'ETB' => 'ET',
            'FJD' => 'FJ', 'FKP' => 'FK', 'GBP' => 'GB GG GS IM JE', 'GEL' => 'GE', 'GHS' => 'GH',
            'GIP' => 'GI', 'GMD' => 'GM', 'GNF' => 'GN', 'GTQ' => 'GT', 'GWP' => 'GW', 'GYD' => 'GY',
            'HKD' => 'HK', 'HNL' => 'HN', 'HRK' => 'HR', 'HTG' => 'HT', 'HUF' => 'HU', 'IDR' => 'ID',
            'ILS' => 'IL', 'IQD' => 'IQ', 'IRR' => 'IR', 'ISK' => 'IS', 'JMD' => 'JM', 'JOD' => 'JO PS',
            'JPY' => 'JP', 'KES' => 'KE', 'KGS' => 'KG', 'KHR' => 'KH', 'KMF' => 'KM', 'KPW' => 'KP',
            'KRW' => 'KR', 'KWD' => 'KW', 'KYD' => 'KY', 'KZT' => 'KZ', 'LAK' => 'LA', 'LBP' => 'LB',
            'LKR' => 'LK', 'LRD' => 'LR', 'ZAR' => 'LS NA ZA', 'LTL' => 'LT', 'LVL' => 'LV', 'LYD' => 'LY',
            'MDL' => 'MD', 'MGA' => 'MG', 'MKD' => 'MK', 'MMK' => 'MM', 'MNT' => 'MN', 'MOP' => 'MO',
            'MRO' => 'MR', 'MUR' => 'MU', 'MVR' => 'MV', 'MWK' => 'MW', 'MXN' => 'MX', 'MYR' => 'MY',
            'MZN' => 'MZ', 'XPF' => 'NC PF WF', 'NGN' => 'NG', 'NIO' => 'NI', 'NPR' => 'NP', 'OMR' => 'OM',
            'PAB' => 'PA', 'PEN' => 'PE', 'PGK' => 'PG', 'PHP' => 'PH', 'PKR' => 'PK', 'PLN' => 'PL',
            'PYG' => 'PY', 'QAR' => 'QA', 'RON' => 'RO', 'RSD' => 'RS', 'RUB' => 'RU', 'RWF' => 'RW',
            'SAR' => 'SA', 'SBD' => 'SB', 'SCR' => 'SC', 'SDG' => 'SD', 'SEK' => 'SE', 'SGD' => 'SG',
            'SHP' => 'SH', 'SKK' => 'SK', 'SLL' => 'SL', 'SOS' => 'SO', 'SRD' => 'SR', 'STD' => 'ST',
            'SVC' => 'SV', 'SYP' => 'SY', 'SZL' => 'SZ', 'THB' => 'TH', 'TJS' => 'TJ', 'TMM' => 'TM',
            'TND' => 'TN', 'TOP' => 'TO', 'TRY' => 'TR', 'TTD' => 'TT', 'TWD' => 'TW', 'TZS' => 'TZ',
            'UAH' => 'UA', 'UGX' => 'UG', 'UYU' => 'UY', 'UZS' => 'UZ', 'VEF' => 'VE', 'VND' => 'VN',
            'VUV' => 'VU', 'WST' => 'WS', 'YER' => 'YE', 'ZMK' => 'ZM', 'ZWD' => 'ZW', 'ZRN' => 'ZR',
            'YUM' => 'YU', 'TPE' => 'TP', 'SUR' => 'SU', 'DDM' => 'DD', 'CSD' => 'CS', 'BUK' => 'BU');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'regiontocurrency', 'EUR');
        $this->assertEquals("AD AT AX BE BL CY DE ES FI FR GF GP GR IE IT LU MC ME MF MQ MT NL PM PT QU RE SI SM TF VA YT", $value);
    }

    /**
     * test for reading regiontoterritory from locale
     * expected array
     */
    public function testRegionToTerritory()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'regiontoterritory');
        $result = array('001' => '002 009 019 142 150',
            '011' => 'BF BJ CI CV GH GM GN GW LR ML MR NE NG SH SL SN TG', '013' => 'BZ CR GT HN MX NI PA SV',
            '014' => 'BI DJ ER ET KE KM MG MU MW MZ RE RW SC SO TZ UG YT ZM ZW',
            '142' => '030 035 143 145 034 062', '143' => 'TM TJ KG KZ UZ',
            '145' => 'AE AM AZ BH CY GE IL IQ JO KW LB OM PS QA SA NT SY TR YE YD',
            '015' => 'DZ EG EH LY MA SD TN', '150' => '039 151 154 155 QU',
            '151' => 'BG BY CZ HU MD PL RO RU SU SK UA',
            '154' => 'GG IM JE AX DK EE FI FO GB IE IM IS LT LV NO SE SJ', '830' => 'GG JE',
            '155' => 'AT BE CH DE DD FR FX LI LU MC NL', '017' => 'AO CD ZR CF CG CM GA GQ ST TD',
            '172' => 'AM AZ BY GE KG KZ MD RU TJ TM UA UZ', '018' => 'BW LS NA SZ ZA',
            '019' => '005 013 021 029 003 419', '002' => '011 014 015 017 018', '021' => 'BM CA GL PM US',
            '029' => 'AG AI AN AW BB BL BS CU DM DO GD GP HT JM KN KY LC MF MQ MS PR TC TT VC VG VI',
            '003' => '013 021 029', '030' => 'CN HK JP KP KR MN MO TW',
            '035' => 'BN ID KH LA MM BU MY PH SG TH TL TP VN',
            '039' => 'AD AL BA ES GI GR HR IT ME MK MT CS RS PT SI SM VA YU', '419' => '005 013 029',
            '005' => 'AR BO BR CL CO EC FK GF GY PE PY SR UY VE', '053' => 'AU NF NZ',
            '054' => 'FJ NC PG SB VU', '057' => 'FM GU KI MH MP NR PW',
            '061' => 'AS CK NU PF PN TK TO TV WF WS', '062' => '034 143', '034' => 'AF BD BT IN IR LK MV NP PK',
            '009' => '053 054 057 061 QO', 'QO' => 'AQ BV CC CX GS HM IO TF UM',
            'QU' => 'AT BE CY CZ DE DK EE ES FI FR GB GR HU IE IT LT LU LV MT NL PL PT SE SI SK BG RO');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'regiontoterritory', '143');
        $this->assertEquals("TM TJ KG KZ UZ", $value);
    }

    /**
     * test for reading territorytoregion from locale
     * expected array
     */
    public function testTerritoryToRegion()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'territorytoregion');
        $result = array('002' => '001', '009' => '001', '019' => '001', '142' => '001', '150' => '001',
            'BF' => '011', 'BJ' => '011', 'CI' => '011', 'CV' => '011', 'GH' => '011', 'GM' => '011',
            'GN' => '011', 'GW' => '011', 'LR' => '011', 'ML' => '011', 'MR' => '011', 'NE' => '011',
            'NG' => '011', 'SH' => '011', 'SL' => '011', 'SN' => '011', 'TG' => '011', 'BZ' => '013',
            'CR' => '013', 'GT' => '013', 'HN' => '013', 'MX' => '013', 'NI' => '013', 'PA' => '013',
            'SV' => '013', 'BI' => '014', 'DJ' => '014', 'ER' => '014', 'ET' => '014', 'KE' => '014',
            'KM' => '014', 'MG' => '014', 'MU' => '014', 'MW' => '014', 'MZ' => '014', 'RE' => '014',
            'RW' => '014', 'SC' => '014', 'SO' => '014', 'TZ' => '014', 'UG' => '014', 'YT' => '014',
            'ZM' => '014', 'ZW' => '014', '030' => '142', '035' => '142', '143' => '142 062', '145' => '142',
            '034' => '142 062', '062' => '142', 'TM' => '143 172', 'TJ' => '143 172', 'KG' => '143 172',
            'KZ' => '143 172', 'UZ' => '143 172', 'AE' => '145', 'AM' => '145 172', 'AZ' => '145 172',
            'BH' => '145', 'CY' => '145 QU', 'GE' => '145 172', 'IL' => '145', 'IQ' => '145', 'JO' => '145',
            'KW' => '145', 'LB' => '145', 'OM' => '145', 'PS' => '145', 'QA' => '145', 'SA' => '145',
            'NT' => '145', 'SY' => '145', 'TR' => '145', 'YE' => '145', 'YD' => '145', 'DZ' => '015',
            'EG' => '015', 'EH' => '015', 'LY' => '015', 'MA' => '015', 'SD' => '015', 'TN' => '015',
            '039' => '150', '151' => '150', '154' => '150', '155' => '150', 'QU' => '150', 'BG' => '151 QU',
            'BY' => '151 172', 'CZ' => '151 QU', 'HU' => '151 QU', 'MD' => '151 172', 'PL' => '151 QU',
            'RO' => '151 QU', 'RU' => '151 172', 'SU' => '151', 'SK' => '151 QU', 'UA' => '151 172',
            'GG' => '154 830', 'IM' => '154 154', 'JE' => '154 830', 'AX' => '154', 'DK' => '154 QU',
            'EE' => '154 QU', 'FI' => '154 QU', 'FO' => '154', 'GB' => '154 QU', 'IE' => '154 QU',
            'IS' => '154', 'LT' => '154 QU', 'LV' => '154 QU', 'NO' => '154', 'SE' => '154 QU', 'SJ' => '154',
            'AT' => '155 QU', 'BE' => '155 QU', 'CH' => '155', 'DE' => '155 QU', 'DD' => '155',
            'FR' => '155 QU', 'FX' => '155', 'LI' => '155', 'LU' => '155 QU', 'MC' => '155', 'NL' => '155 QU',
            'AO' => '017', 'CD' => '017', 'ZR' => '017', 'CF' => '017', 'CG' => '017', 'CM' => '017',
            'GA' => '017', 'GQ' => '017', 'ST' => '017', 'TD' => '017', 'BW' => '018', 'LS' => '018',
            'NA' => '018', 'SZ' => '018', 'ZA' => '018', '005' => '019 419', '013' => '019 003 419',
            '021' => '019 003', '029' => '019 003 419', '003' => '019', '419' => '019', '011' => '002',
            '014' => '002', '015' => '002', '017' => '002', '018' => '002', 'BM' => '021', 'CA' => '021',
            'GL' => '021', 'PM' => '021', 'US' => '021', 'AG' => '029', 'AI' => '029', 'AN' => '029',
            'AW' => '029', 'BB' => '029', 'BS' => '029', 'CU' => '029', 'DM' => '029', 'DO' => '029',
            'GD' => '029', 'GP' => '029', 'HT' => '029', 'JM' => '029', 'KN' => '029', 'KY' => '029',
            'LC' => '029', 'MQ' => '029', 'MS' => '029', 'PR' => '029', 'TC' => '029', 'TT' => '029',
            'VC' => '029', 'VG' => '029', 'VI' => '029', 'CN' => '030', 'HK' => '030', 'JP' => '030',
            'KP' => '030', 'KR' => '030', 'MN' => '030', 'MO' => '030', 'TW' => '030', 'BN' => '035',
            'ID' => '035', 'KH' => '035', 'LA' => '035', 'MM' => '035', 'BU' => '035', 'MY' => '035',
            'PH' => '035', 'SG' => '035', 'TH' => '035', 'TL' => '035', 'TP' => '035', 'VN' => '035',
            'AD' => '039', 'AL' => '039', 'BA' => '039', 'ES' => '039 QU', 'GI' => '039', 'GR' => '039 QU',
            'HR' => '039', 'IT' => '039 QU', 'ME' => '039', 'MK' => '039', 'MT' => '039 QU', 'CS' => '039',
            'RS' => '039', 'PT' => '039 QU', 'SI' => '039 QU', 'SM' => '039', 'VA' => '039', 'YU' => '039',
            'AR' => '005', 'BO' => '005', 'BR' => '005', 'CL' => '005', 'CO' => '005', 'EC' => '005',
            'FK' => '005', 'GF' => '005', 'GY' => '005', 'PE' => '005', 'PY' => '005', 'SR' => '005',
            'UY' => '005', 'VE' => '005', 'AU' => '053', 'NF' => '053', 'NZ' => '053', 'FJ' => '054',
            'NC' => '054', 'PG' => '054', 'SB' => '054', 'VU' => '054', 'FM' => '057', 'GU' => '057',
            'KI' => '057', 'MH' => '057', 'MP' => '057', 'NR' => '057', 'PW' => '057', 'AS' => '061',
            'CK' => '061', 'NU' => '061', 'PF' => '061', 'PN' => '061', 'TK' => '061', 'TO' => '061',
            'TV' => '061', 'WF' => '061', 'WS' => '061', 'AF' => '034', 'BD' => '034', 'BT' => '034',
            'IN' => '034', 'IR' => '034', 'LK' => '034', 'MV' => '034', 'NP' => '034', 'PK' => '034',
            '053' => '009', '054' => '009', '057' => '009', '061' => '009', 'QO' => '009', 'AQ' => 'QO',
            'BV' => 'QO', 'CC' => 'QO', 'CX' => 'QO', 'GS' => 'QO', 'HM' => 'QO', 'IO' => 'QO', 'TF' => 'QO',
            'UM' => 'QO', 'MF' => '029', 'BL' => '029');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'territorytoregion', 'AT');
        $this->assertEquals("155 QU", $value);
    }

    /**
     * test for reading scripttolanguage from locale
     * expected array
     */
    public function testScriptToLanguage()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'scripttolanguage');
        $result = array('aa' => 'Latn', 'ab' => 'Cyrl', 'abq' => 'Cyrl', 'ace' => 'Latn', 'ady' => 'Cyrl',
            'af' => 'Latn', 'aii' => 'Cyrl', 'ain' => 'Kana Latn', 'ak' => 'Latn', 'akk' => 'Xsux',
            'am' => 'Ethi', 'amo' => 'Latn', 'ar' => 'Arab', 'as' => 'Beng', 'ast' => 'Latn', 'av' => 'Cyrl',
            'awa' => 'Deva', 'ay' => 'Latn', 'az' => 'Arab Cyrl Latn', 'ba' => 'Cyrl', 'bal' => 'Arab Latn',
            'ban' => 'Latn', 'bbc' => 'Latn', 'be' => 'Cyrl', 'bem' => 'Latn', 'bfq' => 'Taml', 'bft' => 'Deva',
            'bfy' => 'Deva', 'bg' => 'Cyrl', 'bh' => 'Deva', 'bhb' => 'Deva', 'bho' => 'Deva', 'bi' => 'Latn',
            'bin' => 'Latn', 'bjj' => 'Deva', 'bku' => 'Buhd', 'bm' => 'Latn', 'bn' => 'Beng', 'bo' => 'Tibt',
            'br' => 'Latn', 'bra' => 'Deva', 'bs' => 'Latn', 'btv' => 'Deva', 'buc' => 'Latn', 'bug' => 'Latn',
            'bxr' => 'Cyrl', 'bya' => 'Latn', 'byn' => 'Ethi', 'ca' => 'Latn', 'cch' => 'Latn', 'ccp' => 'Beng',
            'ce' => 'Cyrl', 'ceb' => 'Latn', 'ch' => 'Latn', 'chk' => 'Latn', 'chm' => 'Cyrl Latn',
            'chr' => 'Cher Latn', 'cja' => 'Cham Deva', 'cjm' => 'Arab', 'cjs' => 'Cyrl', 'ckt' => 'Cyrl',
            'co' => 'Latn', 'cop' => 'Arab Copt Grek', 'cpe' => 'Latn', 'cr' => 'Cans Latn', 'crk' => 'Cans',
            'cs' => 'Latn', 'cu' => 'Glag', 'cv' => 'Cyrl', 'cwd' => 'Cans', 'cy' => 'Latn', 'da' => 'Latn',
            'dar' => 'Cyrl', 'de' => 'Latn', 'dgr' => 'Latn', 'dng' => 'Cyrl', 'doi' => 'Arab', 'dv' => 'Thaa',
            'dyu' => 'Latn', 'dz' => 'Tibt', 'ee' => 'Latn', 'efi' => 'Latn', 'el' => 'Grek', 'emk' => 'Deva',
            'en' => 'Latn', 'eo' => 'Latn', 'es' => 'Latn', 'et' => 'Latn', 'ett' => 'Ital Latn',
            'eu' => 'Latn', 'evn' => 'Cyrl', 'fa' => 'Arab', 'fan' => 'Latn', 'fi' => 'Latn', 'fil' => 'Latn',
            'fiu' => 'Latn', 'fj' => 'Latn', 'fo' => 'Latn', 'fon' => 'Latn', 'fr' => 'Latn', 'fur' => 'Latn',
            'fy' => 'Latn', 'ga' => 'Latn', 'gaa' => 'Latn', 'gag' => 'Cyrl', 'gbm' => 'Deva', 'gcr' => 'Latn',
            'gd' => 'Latn', 'gez' => 'Ethi', 'gil' => 'Latn', 'gl' => 'Latn', 'gld' => 'Cyrl', 'gn' => 'Latn',
            'gon' => 'Deva Telu', 'gor' => 'Latn', 'got' => 'Goth', 'grc' => 'Cprt Grek Linb', 'grt' => 'Beng',
            'gsw' => 'Latn', 'gu' => 'Gujr', 'gv' => 'Latn', 'gwi' => 'Latn', 'ha' => 'Arab Latn',
            'hai' => 'Latn', 'haw' => 'Latn', 'he' => 'Hebr', 'hi' => 'Deva', 'hil' => 'Latn', 'hmn' => 'Latn',
            'hne' => 'Deva', 'hnn' => 'Latn', 'ho' => 'Latn', 'hoc' => 'Deva', 'hoj' => 'Deva', 'hop' => 'Latn',
            'hr' => 'Latn', 'ht' => 'Latn', 'hu' => 'Latn', 'hy' => 'Armn', 'ia' => 'Latn', 'ibb' => 'Latn',
            'id' => 'Latn', 'ig' => 'Latn', 'ii' => 'Latn Yiii', 'ik' => 'Latn', 'ilo' => 'Latn',
            'inh' => 'Cyrl', 'is' => 'Latn', 'it' => 'Latn', 'iu' => 'Cans', 'ja' => 'Jpan', 'jv' => 'Latn',
            'ka' => 'Geor', 'kaa' => 'Cyrl', 'kab' => 'Latn', 'kaj' => 'Latn', 'kam' => 'Latn', 'kbd' => 'Cyrl',
            'kca' => 'Cyrl', 'kcg' => 'Latn', 'kdt' => 'Thai', 'kfo' => 'Latn', 'kfr' => 'Deva',
            'kha' => 'Latn', 'khb' => 'Talu', 'kht' => 'Mymr', 'ki' => 'Latn', 'kj' => 'Latn', 'kjh' => 'Cyrl',
            'kk' => 'Cyrl', 'kl' => 'Latn', 'km' => 'Khmr', 'kmb' => 'Latn', 'kn' => 'Knda', 'ko' => 'Kore',
            'koi' => 'Cyrl', 'kok' => 'Deva', 'kos' => 'Latn', 'kpe' => 'Latn', 'kpv' => 'Cyrl',
            'kpy' => 'Cyrl', 'kr' => 'Latn', 'krc' => 'Cyrl', 'krl' => 'Cyrl Latn', 'kru' => 'Deva',
            'ks' => 'Arab Deva', 'ku' => 'Arab Cyrl Latn', 'kum' => 'Cyrl', 'kv' => 'Cyrl Latn', 'kw' => 'Latn',
            'ky' => 'Arab Cyrl', 'la' => 'Latn', 'lad' => 'Hebr', 'lah' => 'Arab', 'lb' => 'Latn',
            'lbe' => 'Cyrl', 'lcp' => 'Thai', 'lep' => 'Lepc', 'lez' => 'Cyrl', 'lg' => 'Latn', 'li' => 'Latn',
            'lif' => 'Deva Limb', 'lis' => 'Latn', 'lmn' => 'Telu', 'ln' => 'Latn', 'lo' => 'Laoo',
            'lol' => 'Latn', 'lt' => 'Latn', 'lu' => 'Latn', 'lua' => 'Latn', 'luo' => 'Latn', 'lut' => 'Latn',
            'lv' => 'Latn', 'lwl' => 'Thai', 'mad' => 'Latn', 'mag' => 'Deva', 'mai' => 'Deva', 'mak' => 'Latn',
            'mdf' => 'Cyrl', 'mdh' => 'Latn', 'mdr' => 'Bugi', 'men' => 'Latn', 'mfe' => 'Latn', 'mg' => 'Latn',
            'mh' => 'Latn', 'mi' => 'Latn', 'min' => 'Latn', 'mk' => 'Cyrl', 'ml' => 'Mlym',
            'mn' => 'Cyrl Mong', 'mnc' => 'Mong', 'mni' => 'Beng', 'mns' => 'Cyrl', 'mnw' => 'Mymr',
            'mo' => 'Latn', 'mos' => 'Latn', 'mr' => 'Deva', 'ms' => 'Latn', 'mt' => 'Latn',
            'muw' => 'Beng Deva', 'mwr' => 'Deva', 'my' => 'Mymr', 'myv' => 'Cyrl', 'na' => 'Latn',
            'nap' => 'Latn', 'nb' => 'Latn', 'nbf' => 'Latn', 'nd' => 'Latn', 'ne' => 'Deva', 'new' => 'Deva',
            'ng' => 'Latn', 'niu' => 'Latn', 'nl' => 'Latn', 'nn' => 'Latn', 'no' => 'Latn', 'nog' => 'Cyrl',
            'nqo' => 'Nkoo', 'nr' => 'Latn', 'nso' => 'Latn', 'nv' => 'Latn', 'ny' => 'Latn', 'nym' => 'Latn',
            'nyn' => 'Latn', 'oc' => 'Latn', 'om' => 'Latn', 'or' => 'Orya', 'os' => 'Cyrl Latn',
            'osc' => 'Ital Latn', 'pa' => 'Guru', 'pag' => 'Latn', 'pam' => 'Latn', 'pap' => 'Latn',
            'pau' => 'Latn', 'peo' => 'Xpeo', 'phn' => 'Phnx', 'pi' => 'Deva Sinh Thai', 'pl' => 'Latn',
            'pon' => 'Latn', 'pra' => 'Khar', 'prd' => 'Arab', 'prg' => 'Latn', 'ps' => 'Arab', 'pt' => 'Latn',
            'qu' => 'Latn', 'rcf' => 'Latn', 'ril' => 'Beng', 'rm' => 'Latn', 'rn' => 'Latn', 'ro' => 'Latn',
            'rom' => 'Cyrl Latn', 'ru' => 'Cyrl', 'rw' => 'Latn', 'sa' => 'Deva Sinh', 'sah' => 'Cyrl',
            'sam' => 'Hebr', 'sas' => 'Latn', 'sat' => 'Beng Deva Olck Orya', 'scn' => 'Latn', 'sco' => 'Latn',
            'sd' => 'Arab Deva', 'se' => 'Latn', 'sel' => 'Cyrl', 'sg' => 'Latn', 'sga' => 'Latn Ogam',
            'shn' => 'Mymr', 'si' => 'Sinh', 'sid' => 'Latn', 'sk' => 'Latn', 'sl' => 'Latn', 'sm' => 'Latn',
            'sma' => 'Latn', 'smi' => 'Latn', 'smj' => 'Latn', 'smn' => 'Latn', 'sms' => 'Latn', 'sn' => 'Latn',
            'snk' => 'Latn', 'so' => 'Latn', 'son' => 'Latn', 'sq' => 'Latn', 'sr' => 'Cyrl Latn',
            'srn' => 'Latn', 'srr' => 'Latn', 'ss' => 'Latn', 'st' => 'Latn', 'su' => 'Latn', 'suk' => 'Latn',
            'sus' => 'Latn', 'sv' => 'Latn', 'sw' => 'Latn', 'swb' => 'Arab', 'syl' => 'Beng', 'syr' => 'Syrc',
            'ta' => 'Taml', 'tab' => 'Cyrl', 'tbw' => 'Latn', 'tcy' => 'Knda', 'tdd' => 'Tale', 'te' => 'Telu',
            'tem' => 'Latn', 'tet' => 'Latn', 'tg' => 'Arab Cyrl Latn', 'th' => 'Thai', 'ti' => 'Ethi',
            'tig' => 'Ethi', 'tiv' => 'Latn', 'tk' => 'Arab Cyrl Latn', 'tkl' => 'Latn', 'tl' => 'Latn',
            'tmh' => 'Latn', 'tn' => 'Latn', 'to' => 'Latn', 'tpi' => 'Latn', 'tr' => 'Latn', 'tru' => 'Latn',
            'ts' => 'Latn', 'tsg' => 'Latn', 'tt' => 'Cyrl', 'tts' => 'Thai', 'ttt' => 'Cyrl', 'tum' => 'Latn',
            'tut' => 'Cyrl', 'tvl' => 'Latn', 'tw' => 'Latn', 'ty' => 'Latn', 'tyv' => 'Cyrl',
            'tzm' => 'Latn Tfng', 'ude' => 'Cyrl', 'udm' => 'Cyrl', 'ug' => 'Arab', 'uga' => 'Ugar',
            'uk' => 'Cyrl', 'uli' => 'Latn', 'umb' => 'Latn', 'ur' => 'Arab', 'uz' => 'Arab Cyrl Latn',
            'vai' => 'Vaii', 've' => 'Latn', 'vi' => 'Latn', 'vo' => 'Latn', 'wa' => 'Latn', 'wal' => 'Ethi',
            'war' => 'Latn', 'wo' => 'Latn', 'xal' => 'Cyrl', 'xh' => 'Latn', 'xsr' => 'Deva',
            'xum' => 'Ital Latn', 'yao' => 'Latn', 'yap' => 'Latn', 'yi' => 'Hebr', 'yo' => 'Latn',
            'yrk' => 'Cyrl', 'za' => 'Hani', 'zh' => 'Hans Hant', 'zu' => 'Latn', 'zbl' => 'Blis', 'nds' => 'Latn',
            'hsb' => 'Latn', 'frs' => 'Latn', 'frr' => 'Latn', 'dsb' => 'Latn');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'scripttolanguage', 'uk');
        $this->assertEquals("Cyrl", $value);
    }

    /**
     * test for reading languagetoscript from locale
     * expected array
     */
    public function testLanguageToScript()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'languagetoscript');
        $result = array(
            'Latn' => 'aa ace af ain ak amo ast ay az bal ban bbc bem bi bin bm br bs buc bug bya ca cch ceb ch chk chm chr co cpe cr cs cy da de dgr dsb dyu ee efi en eo es et ett eu fan fi fil fiu fj fo fon fr frr frs fur fy ga gaa gcr gd gil gl gn gor gsw gv gwi ha hai haw hil hmn hnn ho hop hr hsb ht hu ia ibb id ig ii ik ilo is it jv kab kaj kam kcg kfo kha ki kj kl kmb kos kpe kr krl ku kv kw la lb lg li lis ln lol lt lu lua luo lut lv mad mak mdh men mfe mg mh mi min mo mos ms mt na nap nb nbf nd nds ng niu nl nn no nr nso nv ny nym nyn oc om os osc pag pam pap pau pl pon prg pt qu rcf rm rn ro rom rw sas scn sco se sg sga sid sk sl sm sma smi smj smn sms sn snk so son sq sr srn srr ss st su suk sus sv sw tbw tem tet tg tiv tk tkl tl tmh tn to tpi tr tru ts tsg tum tvl tw ty tzm uli umb uz ve vi vo wa war wo xh xum yao yap yo zu',
            'Cyrl' => 'ab abq ady aii av az ba be bg bxr ce chm cjs ckt cv dar dng evn gag gld inh kaa kbd kca kjh kk koi kpv kpy krc krl ku kum kv ky lbe lez mdf mk mn mns myv nog os rom ru sah sel sr tab tg tk tt ttt tut tyv ude udm uk uz xal yrk',
            'Kana' => 'ain', 'Xsux' => 'akk', 'Ethi' => 'am byn gez ti tig wal',
            'Arab' => 'ar az bal cjm cop doi fa ha ks ku ky lah prd ps sd swb tg tk ug ur uz',
            'Beng' => 'as bn ccp grt mni muw ril sat syl',
            'Deva' => 'awa bft bfy bh bhb bho bjj bra btv cja emk gbm gon hi hne hoc hoj kfr kok kru ks lif mag mai mr muw mwr ne new pi sa sat sd xsr',
            'Taml' => 'bfq ta', 'Buhd' => 'bku', 'Tibt' => 'bo dz', 'Cher' => 'chr', 'Cham' => 'cja',
            'Copt' => 'cop', 'Grek' => 'cop el grc', 'Cans' => 'cr crk cwd iu', 'Glag' => 'cu', 'Thaa' => 'dv',
            'Ital' => 'ett osc xum', 'Telu' => 'gon lmn te', 'Goth' => 'got', 'Cprt' => 'grc', 'Linb' => 'grc',
            'Gujr' => 'gu', 'Hebr' => 'he lad sam yi', 'Armn' => 'hy', 'Yiii' => 'ii', 'Jpan' => 'ja',
            'Geor' => 'ka', 'Thai' => 'kdt lcp lwl pi th tts', 'Talu' => 'khb', 'Mymr' => 'kht mnw my shn',
            'Khmr' => 'km', 'Knda' => 'kn tcy', 'Lepc' => 'lep', 'Limb' => 'lif',
            'Laoo' => 'lo', 'Bugi' => 'mdr', 'Mlym' => 'ml', 'Mong' => 'mn mnc', 'Nkoo' => 'nqo',
            'Orya' => 'or sat', 'Guru' => 'pa', 'Xpeo' => 'peo', 'Phnx' => 'phn', 'Sinh' => 'pi sa si',
            'Khar' => 'pra', 'Olck' => 'sat', 'Ogam' => 'sga', 'Syrc' => 'syr', 'Tale' => 'tdd',
            'Tfng' => 'tzm', 'Ugar' => 'uga', 'Vaii' => 'vai', 'Hani' => 'za', 'Hans' => 'zh', 'Hant' => 'zh',
            'Blis' => 'zbl', 'Kore' => 'ko');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'languagetoscript', 'Kana');
        $this->assertEquals("ain", $value);
    }

    /**
     * test for reading territorytolanguage from locale
     * expected array
     */
    public function testTerritoryToLanguage()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'territorytolanguage');
        $result = array('aa' => 'DJ', 'ab' => 'GE', 'abr' => 'GH', 'ace' => 'ID', 'ady' => 'RU', 'af' => 'ZA',
            'ak' => 'GH', 'am' => 'ET', 'ar' => 'AE BH DJ DZ EG EH ER IL IQ JO KM KW LB LY MA MR OM PS QA SA SD SY TD TN YE',
            'as' => 'IN', 'ast' => 'ES', 'av' => 'RU', 'awa' => 'IN', 'ay' => 'BO', 'az' => 'AZ',
            'ba' => 'RU', 'bal' => 'PK', 'ban' => 'ID', 'bbc' => 'ID', 'bcl' => 'PH', 'be' => 'BY',
            'bem' => 'ZM', 'bew' => 'ID', 'bg' => 'BG', 'bgc' => 'IN', 'bhb' => 'IN', 'bhi' => 'IN',
            'bhk' => 'PH', 'bho' => 'IN MU NP', 'bi' => 'VU', 'bin' => 'NG', 'bjj' => 'IN', 'bjn' => 'ID',
            'bm' => 'ML', 'bn' => 'BD IN', 'bo' => 'CN', 'bqi' => 'IR', 'brh' => 'PK', 'bs' => 'BA',
            'buc' => 'YT', 'bug' => 'ID', 'bya' => 'ID', 'ca' => 'AD', 'ce' => 'RU', 'ceb' => 'PH',
            'cgg' => 'UG', 'ch' => 'GU', 'chk' => 'FM', 'crk' => 'CA', 'cs' => 'CZ', 'cv' => 'RU',
            'cwd' => 'CA', 'cy' => 'GB', 'da' => 'DK GL', 'dcc' => 'IN', 'de' => 'AT BE CH DE LI LU',
            'dhd' => 'IN', 'diq' => 'TR', 'dje' => 'NE', 'doi' => 'IN', 'dv' => 'MV', 'dyu' => 'BF',
            'dz' => 'BT', 'ee' => 'GH', 'efi' => 'NG', 'el' => 'CY GR', 'emk' => 'GN',
            'en' => 'AG AI AS AU BB BM BS BW BZ CA CC CK CM CX DM FJ FK FM GB GD GG GH GI GM GU GY HK HN IE IM JE JM KE KI KN KY LC LR LS MH MP MS MT MU MW NA NF NG NR NU NZ PG PH PK PN PR RW SB SC SG SH SL SZ TC TK TO TT TV TZ UG UM US VC VG VI VU WS ZA ZM ZW',
            'es' => 'AR BO CL CO CR CU DO EC ES GQ GT HN MX NI PA PE PH PR PY SV UY VE', 'et' => 'EE',
            'eu' => 'ES', 'fa' => 'AF IR', 'fan' => 'GQ', 'fi' => 'FI', 'fil' => 'PH', 'fj' => 'FJ', 'fo' => 'FO', 'fon' => 'BJ',
            'fr' => 'BE BF BI BJ BL CA CD CF CG CH CI CM DJ DZ FR GA GF GN GP GQ HT KM LU MA MC MF MG ML MQ MU NC NE PF PM RE RW SC SN SY TD TG TN VU WF YT',
            'fud' => 'WF', 'fuv' => 'NG', 'fy' => 'NL', 'ga' => 'IE', 'gaa' => 'GH', 'gbm' => 'IN',
            'gcr' => 'GF', 'gd' => 'GB', 'gil' => 'KI', 'gl' => 'ES', 'glk' => 'IR', 'gn' => 'PY',
            'gno' => 'IN', 'gon' => 'IN', 'gsw' => 'CH LI', 'gu' => 'IN', 'guz' => 'KE', 'ha' => 'NG',
            'haw' => 'US', 'haz' => 'AF', 'he' => 'IL', 'hi' => 'IN', 'hil' => 'PH', 'hne' => 'IN',
            'hno' => 'PK', 'ho' => 'PG', 'hoc' => 'IN', 'hr' => 'BA HR', 'ht' => 'HT', 'hu' => 'HU',
            'hy' => 'AM', 'ibb' => 'NG', 'id' => 'ID', 'ig' => 'NG', 'ii' => 'CN', 'ilo' => 'PH', 'inh' => 'RU',
            'is' => 'IS', 'it' => 'CH IT SM', 'iu' => 'CA GL', 'ja' => 'JP', 'jv' => 'ID', 'ka' => 'GE',
            'kab' => 'DZ', 'kam' => 'KE', 'kbd' => 'RU', 'kfy' => 'IN', 'kha' => 'IN', 'khn' => 'IN',
            'ki' => 'KE', 'kj' => 'NA', 'kk' => 'KZ', 'kl' => 'GL', 'kln' => 'KE', 'km' => 'KH', 'kmb' => 'AO',
            'kn' => 'IN', 'ko' => 'KP KR', 'koi' => 'RU', 'kok' => 'IN', 'kos' => 'FM',
            'kpv' => 'RU', 'krc' => 'RU', 'kri' => 'SL', 'kru' => 'IN', 'ks' => 'IN', 'ku' => 'IQ IR SY TR',
            'kum' => 'RU', 'kxm' => 'TH', 'ky' => 'KG', 'la' => 'VA', 'lah' => 'PK', 'lb' => 'LU',
            'lbe' => 'RU', 'lez' => 'RU', 'lg' => 'UG', 'ljp' => 'ID', 'lmn' => 'IN', 'ln' => 'CD CG',
            'lo' => 'LA', 'lrc' => 'IR', 'lt' => 'LT', 'lu' => 'CD', 'lua' => 'CD', 'luo' => 'KE',
            'luy' => 'KE', 'lv' => 'LV', 'mad' => 'ID', 'mag' => 'IN', 'mai' => 'IN NP', 'mak' => 'ID',
            'mdf' => 'RU', 'mdh' => 'PH', 'men' => 'SL', 'mer' => 'KE', 'mfa' => 'TH', 'mfe' => 'MU',
            'mg' => 'MG', 'mh' => 'MH', 'mi' => 'NZ', 'min' => 'ID', 'mk' => 'MK', 'ml' => 'IN', 'mn' => 'MN',
            'mni' => 'IN', 'mos' => 'BF', 'mr' => 'IN', 'ms' => 'BN MY SG', 'mt' => 'MT', 'mtr' => 'IN',
            'mup' => 'IN', 'muw' => 'IN', 'my' => 'MM', 'myv' => 'RU', 'na' => 'NR', 'nap' => 'IT',
            'nb' => 'NO SJ', 'nd' => 'ZW', 'ndc' => 'MZ', 'ne' => 'NP', 'ng' => 'NA', 'ngl' => 'MZ',
            'niu' => 'NU', 'nl' => 'AN AW BE NL SR', 'nn' => 'NO', 'nod' => 'TH', 'noe' => 'IN', 'nso' => 'ZA',
            'ny' => 'MW', 'nym' => 'TZ', 'nyn' => 'UG', 'om' => 'ET', 'or' => 'IN', 'os' => 'GE', 'pa' => 'IN',
            'pag' => 'PH', 'pam' => 'PH', 'pap' => 'AN', 'pau' => 'PW', 'pl' => 'PL', 'pon' => 'FM',
            'ps' => 'AF', 'pt' => 'AO BR CV GW MZ PT ST TL', 'qu' => 'BO PE', 'rcf' => 'RE', 'rej' => 'ID',
            'rif' => 'MA', 'rjb' => 'IN', 'rm' => 'CH', 'rmt' => 'IR', 'rn' => 'BI', 'ro' => 'MD RO',
            'ru' => 'BY KG KZ RU', 'rw' => 'RW', 'sa' => 'IN', 'sah' => 'RU', 'sas' => 'ID', 'sat' => 'IN',
            'sck' => 'IN', 'scn' => 'IT', 'sco' => 'GB', 'sd' => 'IN', 'se' => 'NO', 'sg' => 'CF',
            'shn' => 'MM', 'si' => 'LK', 'sid' => 'ET', 'sk' => 'SK', 'sl' => 'SI', 'sm' => 'AS WS',
            'sn' => 'ZW', 'so' => 'SO', 'sou' => 'TH', 'sq' => 'AL', 'sr' => 'BA ME RS', 'srn' => 'SR',
            'srr' => 'SN', 'ss' => 'SZ', 'st' => 'LS ZA', 'su' => 'ID', 'suk' => 'TZ', 'sv' => 'AX FI SE',
            'sw' => 'KE TZ UG', 'swb' => 'KM', 'swv' => 'IN', 'syl' => 'BD', 'ta' => 'IN LK SG', 'tcy' => 'IN',
            'te' => 'IN', 'tem' => 'SL', 'tet' => 'TL', 'tg' => 'TJ', 'th' => 'TH', 'ti' => 'ER', 'tiv' => 'NG',
            'tk' => 'TM', 'tkl' => 'TK', 'tl' => 'PH US', 'tn' => 'BW ZA', 'to' => 'TO', 'tpi' => 'PG',
            'tr' => 'CY TR', 'ts' => 'ZA', 'tsg' => 'PH', 'tt' => 'RU', 'tts' => 'TH', 'tvl' => 'TV',
            'tw' => 'GH', 'ty' => 'PF', 'tyv' => 'RU', 'tzm' => 'MA', 'udm' => 'RU', 'ug' => 'CN', 'uk' => 'UA',
            'uli' => 'FM', 'umb' => 'AO', 'und' => 'AQ BV GS HM IO TF', 'ur' => 'IN PK', 'uz' => 'UZ',
            've' => 'ZA', 'vi' => 'VN', 'vmw' => 'MZ', 'wal' => 'ET', 'war' => 'PH', 'wbq' => 'IN',
            'wbr' => 'IN', 'wls' => 'WF', 'wo' => 'SN', 'wtm' => 'IN', 'xh' => 'ZA', 'xnr' => 'IN',
            'xog' => 'UG', 'yap' => 'FM', 'yo' => 'NG', 'za' => 'CN', 'zh' => 'CN HK MO SG TW', 'zu' => 'ZA',
            'oc' => 'FR', 'kg' => 'CD');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'territorytolanguage', 'uk');
        $this->assertEquals("UA", $value);
    }

    /**
     * test for reading languagetoterritory from locale
     * expected array
     */
    public function testLanguageToTerritory()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'languagetoterritory');
        $result = array('DJ' => 'aa ar fr', 'GE' => 'ab ka os', 'GH' => 'abr ak ee en gaa tw',
            'ID' => 'ace ban bbc bew bjn bug bya id jv ljp mad mak min rej sas su',
            'RU' => 'ady av ba ce cv inh kbd koi kpv krc kum lbe lez mdf myv ru sah tt tyv udm',
            'ZA' => 'af en nso st tn ts ve xh zu', 'ET' => 'am om sid wal', 'AE' => 'ar', 'BH' => 'ar',
            'DZ' => 'ar fr kab', 'EG' => 'ar', 'EH' => 'ar', 'ER' => 'ar ti', 'IL' => 'ar he', 'IQ' => 'ar ku',
            'JO' => 'ar', 'KM' => 'ar fr swb', 'KW' => 'ar', 'LB' => 'ar', 'LY' => 'ar',
            'MA' => 'ar fr rif tzm', 'MR' => 'ar', 'OM' => 'ar', 'PS' => 'ar', 'QA' => 'ar', 'SA' => 'ar',
            'SD' => 'ar', 'SY' => 'ar fr ku', 'TD' => 'ar fr', 'TN' => 'ar fr', 'YE' => 'ar',
            'IN' => 'as awa bgc bhb bhi bho bjj bn dcc dhd doi gbm gno gon gu hi hne hoc kfy kha khn kn kok kru ks lmn mag mai ml mni mr mtr mup muw noe or pa rjb sa sat sck sd swv ta tcy te ur wbq wbr wtm xnr',
            'ES' => 'ast es eu gl', 'FR' => 'fr oc', 'BO' => 'ay es qu', 'AZ' => 'az',
            'PK' => 'bal brh en hno lah ur', 'PH' => 'bcl bhk ceb en es fil hil ilo mdh pag pam tl tsg war',
            'BY' => 'be ru', 'ZM' => 'bem en', 'BG' => 'bg', 'MU' => 'bho en fr mfe', 'NP' => 'bho mai ne',
            'VU' => 'bi en fr', 'NG' => 'bin efi en fuv ha ibb ig tiv yo', 'ML' => 'bm fr', 'BD' => 'bn syl',
            'CN' => 'bo ii ug za zh', 'IR' => 'bqi fa glk ku lrc rmt', 'BA' => 'bs hr sr', 'YT' => 'buc fr',
            'AD' => 'ca', 'UG' => 'cgg en lg nyn sw xog', 'GU' => 'ch en', 'FM' => 'chk en kos pon uli yap',
            'CA' => 'crk cwd en fr iu', 'CZ' => 'cs', 'GB' => 'cy en gd sco', 'DK' => 'da', 'GL' => 'da iu kl',
            'AT' => 'de', 'BE' => 'de fr nl', 'CH' => 'de fr gsw it rm', 'DE' => 'de', 'LI' => 'de gsw',
            'LU' => 'de fr lb', 'TR' => 'diq ku tr', 'NE' => 'dje fr', 'MV' => 'dv', 'BF' => 'dyu fr mos',
            'BT' => 'dz', 'CY' => 'el tr', 'GR' => 'el', 'GN' => 'emk fr', 'AG' => 'en', 'AI' => 'en',
            'AS' => 'en sm', 'AU' => 'en', 'BB' => 'en', 'BM' => 'en', 'BS' => 'en', 'BW' => 'en tn',
            'BZ' => 'en', 'CC' => 'en', 'CK' => 'en', 'CM' => 'en fr', 'CX' => 'en', 'DM' => 'en',
            'FJ' => 'en fj', 'FK' => 'en', 'GD' => 'en', 'GG' => 'en', 'GI' => 'en', 'GM' => 'en', 'GY' => 'en',
            'HK' => 'en zh', 'HN' => 'en es', 'IE' => 'en ga', 'IM' => 'en', 'JE' => 'en', 'JM' => 'en',
            'KE' => 'en guz kam ki kln luo luy mer sw', 'KI' => 'en gil', 'KN' => 'en', 'KY' => 'en',
            'LC' => 'en', 'LR' => 'en', 'LS' => 'en st', 'MH' => 'en mh', 'MP' => 'en', 'MS' => 'en',
            'MT' => 'en mt', 'MW' => 'en ny', 'NA' => 'en kj ng', 'NF' => 'en', 'NR' => 'en na',
            'NU' => 'en niu', 'NZ' => 'en mi', 'PG' => 'en ho tpi', 'PN' => 'en', 'PR' => 'en es',
            'RW' => 'en fr rw', 'SB' => 'en', 'SC' => 'en fr', 'SG' => 'en ms ta zh', 'SH' => 'en',
            'SL' => 'en kri men tem', 'SZ' => 'en ss', 'TC' => 'en', 'TK' => 'en tkl', 'TO' => 'en to',
            'TT' => 'en', 'TV' => 'en tvl', 'TZ' => 'en nym suk sw', 'UM' => 'en', 'US' => 'en haw tl',
            'VC' => 'en', 'VG' => 'en', 'VI' => 'en', 'WS' => 'en sm', 'ZW' => 'en nd sn', 'AR' => 'es',
            'CL' => 'es', 'CO' => 'es', 'CR' => 'es', 'CU' => 'es', 'DO' => 'es', 'EC' => 'es',
            'GQ' => 'es fan fr', 'GT' => 'es', 'MX' => 'es', 'NI' => 'es', 'PA' => 'es', 'PE' => 'es qu',
            'PY' => 'es gn', 'SV' => 'es', 'UY' => 'es', 'VE' => 'es', 'EE' => 'et', 'AF' => 'fa haz ps',
            'FI' => 'fi sv', 'FO' => 'fo', 'BJ' => 'fon fr', 'BI' => 'fr rn', 'CD' => 'fr kg ln lu lua',
            'CF' => 'fr sg', 'CG' => 'fr ln', 'CI' => 'fr', 'GA' => 'fr', 'GF' => 'fr gcr', 'GP' => 'fr',
            'HT' => 'fr ht', 'MC' => 'fr', 'MG' => 'fr mg', 'MQ' => 'fr', 'NC' => 'fr', 'PF' => 'fr ty',
            'PM' => 'fr', 'RE' => 'fr rcf', 'SN' => 'fr srr wo', 'TG' => 'fr', 'WF' => 'fr fud wls',
            'NL' => 'fy nl', 'HR' => 'hr', 'HU' => 'hu', 'AM' => 'hy', 'IS' => 'is', 'IT' => 'it nap scn',
            'SM' => 'it', 'JP' => 'ja', 'KZ' => 'kk ru', 'KH' => 'km', 'AO' => 'kmb pt umb', 'KP' => 'ko',
            'KR' => 'ko', 'TH' => 'kxm mfa nod sou th tts', 'KG' => 'ky ru', 'VA' => 'la', 'LA' => 'lo',
            'LT' => 'lt', 'LV' => 'lv', 'MK' => 'mk', 'MN' => 'mn', 'BN' => 'ms', 'MY' => 'ms',
            'MM' => 'my shn', 'NO' => 'nb nn se', 'SJ' => 'nb', 'MZ' => 'ndc ngl pt vmw', 'AN' => 'nl pap',
            'AW' => 'nl', 'SR' => 'nl srn', 'PW' => 'pau', 'PL' => 'pl', 'BR' => 'pt', 'CV' => 'pt',
            'GW' => 'pt', 'PT' => 'pt', 'ST' => 'pt', 'TL' => 'pt tet', 'MD' => 'ro', 'RO' => 'ro',
            'LK' => 'si ta', 'SK' => 'sk', 'SI' => 'sl', 'SO' => 'so', 'AL' => 'sq', 'ME' => 'sr', 'RS' => 'sr',
            'AX' => 'sv', 'SE' => 'sv', 'TJ' => 'tg', 'TM' => 'tk', 'UA' => 'uk', 'AQ' => 'und', 'BV' => 'und',
            'GS' => 'und', 'HM' => 'und', 'IO' => 'und', 'TF' => 'und', 'UZ' => 'uz', 'VN' => 'vi',
            'MO' => 'zh', 'TW' => 'zh', 'BL' => 'fr', 'MF' => 'fr');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'languagetoterritory', 'GQ');
        $this->assertEquals("es fan fr", $value);
    }

    /**
     * test for reading timezonetowindows from locale
     * expected array
     */
    public function testTimezoneToWindows()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'timezonetowindows');
        $result = array('Dateline' => 'Pacific/Kwajalein', 'Samoa' => 'Pacific/Apia',
            'Hawaiian' => 'Pacific/Honolulu', 'Alaskan' => 'America/Anchorage', 'Pacific' => 'America/Los_Angeles',
            'Pacific Standard Time (Mexico)' => 'America/Tijuana', 'US Mountain' => 'America/Phoenix',
            'Mountain' => 'America/Denver', 'Mountain Standard Time (Mexico)' => 'America/Chihuahua',
            'Mexico Standard Time 2' => 'America/Chihuahua', 'Central America' => 'America/Guatemala',
            'Canada Central' => 'America/Regina', 'Central Standard Time (Mexico)' => 'America/Mexico_City',
            'Mexico' => 'America/Mexico_City', 'Central' => 'America/Chicago', 'US Eastern' => 'America/Indianapolis',
            'SA Pacific' => 'America/Bogota', 'Eastern' => 'America/New_York', 'SA Western' => 'America/Caracas',
            'Pacific SA' => 'America/Santiago', 'Atlantic' => 'America/Halifax', 'Central Brazilian' => 'America/Manaus',
            'Newfoundland' => 'America/St_Johns', 'SA Eastern' => 'America/Buenos_Aires', 'Greenland' => 'America/Godthab',
            'E. South America' => 'America/Sao_Paulo', 'Montevideo' => 'America/Montevideo', 'Mid-Atlantic' => 'America/Noronha',
            'Cape Verde' => 'Atlantic/Cape_Verde', 'Azores' => 'Atlantic/Azores', 'Greenwich' => 'Africa/Casablanca',
            'GMT' => 'Europe/London', 'W. Central Africa' => 'Africa/Lagos', 'W. Europe' => 'Europe/Berlin',
            'Romance' => 'Europe/Paris', 'Central European' => 'Europe/Warsaw', 'Central Europe' => 'Europe/Prague',
            'South Africa' => 'Africa/Johannesburg', 'Israel' => 'Asia/Jerusalem', 'GTB' => 'Europe/Istanbul',
            'FLE' => 'Europe/Helsinki', 'Egypt' => 'Africa/Cairo', 'E. Europe' => 'Europe/Minsk',
            'Jordan' => 'Asia/Amman', 'Middle East' => 'Asia/Beirut', 'Namibia' => 'Africa/Windhoek',
            'E. Africa' => 'Africa/Nairobi', 'Azerbaijan' => 'Asia/Baku', 'Arab' => 'Asia/Riyadh',
            'Georgian' => 'Asia/Tbilisi', 'Russian' => 'Europe/Moscow', 'Arabic' => 'Asia/Baghdad',
            'Iran' => 'Asia/Tehran', 'Arabian' => 'Asia/Muscat', 'Caucasus' => 'Asia/Yerevan', 'Afghanistan' => 'Asia/Kabul',
            'West Asia' => 'Asia/Karachi', 'Ekaterinburg' => 'Asia/Yekaterinburg', 'India' => 'Asia/Calcutta',
            'Nepal' => 'Asia/Katmandu', 'Sri Lanka' => 'Asia/Colombo', 'Central Asia' => 'Asia/Dhaka',
            'N. Central Asia' => 'Asia/Novosibirsk', 'Myanmar' => 'Asia/Rangoon', 'SE Asia' => 'Asia/Bangkok',
            'North Asia' => 'Asia/Krasnoyarsk', 'W. Australia' => 'Australia/Perth', 'Taipei' => 'Asia/Taipei',
            'Singapore' => 'Asia/Singapore', 'China' => 'Asia/Shanghai', 'North Asia East' => 'Asia/Ulaanbaatar',
            'Tokyo' => 'Asia/Tokyo', 'Korea' => 'Asia/Seoul', 'Yakutsk' => 'Asia/Yakutsk', 'AUS Central' => 'Australia/Darwin',
            'Cen. Australia' => 'Australia/Adelaide', 'West Pacific' => 'Pacific/Guam', 'E. Australia' => 'Australia/Brisbane',
            'Vladivostok' => 'Asia/Vladivostok', 'Tasmania' => 'Australia/Hobart', 'AUS Eastern' => 'Australia/Sydney',
            'Central Pacific' => 'Pacific/Guadalcanal', 'Fiji' => 'Pacific/Fiji', 'New Zealand' => 'Pacific/Auckland',
            'Tonga' => 'Pacific/Tongatapu');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'timezonetowindows', 'Fiji');
        $this->assertEquals("Pacific/Fiji", $value);
    }

    /**
     * test for reading windowstotimezone from locale
     * expected array
     */
    public function testWindowsToTimezone()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'windowstotimezone');
        $result = array('Pacific/Kwajalein' => 'Dateline', 'Pacific/Apia' => 'Samoa', 'Pacific/Honolulu' => 'Hawaiian',
            'America/Anchorage' => 'Alaskan', 'America/Los_Angeles' => 'Pacific', 'America/Tijuana' => 'Pacific Standard Time (Mexico)',
            'America/Phoenix' => 'US Mountain', 'America/Denver' => 'Mountain', 'America/Chihuahua' => 'Mountain Standard Time (Mexico)',
            'America/Guatemala' => 'Central America', 'America/Regina' => 'Canada Central', 'America/Mexico_City' => 'Central Standard Time (Mexico)',
            'America/Chicago' => 'Central', 'America/Indianapolis' => 'US Eastern', 'America/Bogota' => 'SA Pacific',
            'America/New_York' => 'Eastern', 'America/Caracas' => 'SA Western', 'America/Santiago' => 'Pacific SA',
            'America/Halifax' => 'Atlantic', 'America/Manaus' => 'Central Brazilian', 'America/St_Johns' => 'Newfoundland',
            'America/Buenos_Aires' => 'SA Eastern', 'America/Godthab' => 'Greenland', 'America/Sao_Paulo' => 'E. South America',
            'America/Montevideo' => 'Montevideo', 'America/Noronha' => 'Mid-Atlantic', 'Atlantic/Cape_Verde' => 'Cape Verde',
            'Atlantic/Azores' => 'Azores', 'Africa/Casablanca' => 'Greenwich', 'Europe/London' => 'GMT',
            'Africa/Lagos' => 'W. Central Africa', 'Europe/Berlin' => 'W. Europe', 'Europe/Paris' => 'Romance',
            'Europe/Warsaw' => 'Central European', 'Europe/Prague' => 'Central Europe', 'Africa/Johannesburg' => 'South Africa',
            'Asia/Jerusalem' => 'Israel', 'Europe/Istanbul' => 'GTB', 'Europe/Helsinki' => 'FLE',
            'Africa/Cairo' => 'Egypt', 'Europe/Minsk' => 'E. Europe', 'Asia/Amman' => 'Jordan', 'Asia/Beirut' => 'Middle East',
            'Africa/Windhoek' => 'Namibia', 'Africa/Nairobi' => 'E. Africa', 'Asia/Baku' => 'Azerbaijan',
            'Asia/Riyadh' => 'Arab', 'Asia/Tbilisi' => 'Georgian', 'Europe/Moscow' => 'Russian', 'Asia/Baghdad' => 'Arabic',
            'Asia/Tehran' => 'Iran', 'Asia/Muscat' => 'Arabian', 'Asia/Yerevan' => 'Caucasus', 'Asia/Kabul' => 'Afghanistan',
            'Asia/Karachi' => 'West Asia', 'Asia/Yekaterinburg' => 'Ekaterinburg', 'Asia/Calcutta' => 'India',
            'Asia/Katmandu' => 'Nepal', 'Asia/Colombo' => 'Sri Lanka', 'Asia/Dhaka' => 'Central Asia', 'Asia/Novosibirsk' => 'N. Central Asia',
            'Asia/Rangoon' => 'Myanmar', 'Asia/Bangkok' => 'SE Asia', 'Asia/Krasnoyarsk' => 'North Asia', 'Australia/Perth' => 'W. Australia',
            'Asia/Taipei' => 'Taipei', 'Asia/Singapore' => 'Singapore', 'Asia/Shanghai' => 'China', 'Asia/Ulaanbaatar' => 'North Asia East',
            'Asia/Tokyo' => 'Tokyo', 'Asia/Seoul' => 'Korea', 'Asia/Yakutsk' => 'Yakutsk', 'Australia/Darwin' => 'AUS Central',
            'Australia/Adelaide' => 'Cen. Australia', 'Pacific/Guam' => 'West Pacific', 'Australia/Brisbane' => 'E. Australia',
            'Asia/Vladivostok' => 'Vladivostok', 'Australia/Hobart' => 'Tasmania', 'Australia/Sydney' => 'AUS Eastern',
            'Pacific/Guadalcanal' => 'Central Pacific', 'Pacific/Fiji' => 'Fiji', 'Pacific/Auckland' => 'New Zealand',
            'Pacific/Tongatapu' => 'Tonga');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'windowstotimezone', 'Pacific/Fiji');
        $this->assertEquals("Fiji", $value);
    }

    /**
     * test for reading territorytotimezone from locale
     * expected array
     */
    public function testTerritoryToTimezone()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'territorytotimezone');
        $result = array('Africa/Abidjan' => 'CI', 'Africa/Accra' => 'GH', 'Africa/Addis_Ababa' => 'ET',
            'Africa/Algiers' => 'DZ', 'Africa/Asmera' => 'ER', 'Africa/Bamako' => 'ML', 'Africa/Bangui' => 'CF',
            'Africa/Banjul' => 'GM', 'Africa/Bissau' => 'GW', 'Africa/Blantyre' => 'MW', 'Africa/Brazzaville' => 'CG',
            'Africa/Bujumbura' => 'BI', 'Africa/Cairo' => 'EG', 'Africa/Casablanca' => 'MA', 'Africa/Ceuta' => 'ES',
            'Africa/Conakry' => 'GN', 'Africa/Dakar' => 'SN', 'Africa/Dar_es_Salaam' => 'TZ', 'Africa/Djibouti' => 'DJ',
            'Africa/Douala' => 'CM', 'Africa/El_Aaiun' => 'EH', 'Africa/Freetown' => 'SL', 'Africa/Gaborone' => 'BW',
            'Africa/Harare' => 'ZW', 'Africa/Johannesburg' => 'ZA', 'Africa/Kampala' => 'UG', 'Africa/Khartoum' => 'SD',
            'Africa/Kigali' => 'RW', 'Africa/Kinshasa' => 'CD', 'Africa/Lagos' => 'NG', 'Africa/Libreville' => 'GA',
            'Africa/Lome' => 'TG', 'Africa/Luanda' => 'AO', 'Africa/Lubumbashi' => 'CD', 'Africa/Lusaka' => 'ZM',
            'Africa/Malabo' => 'GQ', 'Africa/Maputo' => 'MZ', 'Africa/Maseru' => 'LS', 'Africa/Mbabane' => 'SZ',
            'Africa/Mogadishu' => 'SO', 'Africa/Monrovia' => 'LR', 'Africa/Nairobi' => 'KE', 'Africa/Ndjamena' => 'TD',
            'Africa/Niamey' => 'NE', 'Africa/Nouakchott' => 'MR', 'Africa/Ouagadougou' => 'BF', 'Africa/Porto-Novo' => 'BJ',
            'Africa/Sao_Tome' => 'ST', 'Africa/Tripoli' => 'LY', 'Africa/Tunis' => 'TN', 'Africa/Windhoek' => 'NA',
            'America/Adak' => 'US', 'America/Anchorage' => 'US', 'America/Anguilla' => 'AI', 'America/Antigua' => 'AG',
            'America/Araguaina' => 'BR', 'America/Argentina/La_Rioja' => 'AR', 'America/Argentina/Rio_Gallegos' => 'AR',
            'America/Argentina/San_Juan' => 'AR', 'America/Argentina/Tucuman' => 'AR', 'America/Argentina/Ushuaia' => 'AR',
            'America/Aruba' => 'AW', 'America/Asuncion' => 'PY', 'America/Bahia' => 'BR', 'America/Barbados' => 'BB',
            'America/Belem' => 'BR', 'America/Belize' => 'BZ', 'America/Blanc-Sablon' => 'CA', 'America/Boa_Vista' => 'BR',
            'America/Bogota' => 'CO', 'America/Boise' => 'US', 'America/Buenos_Aires' => 'AR', 'America/Cambridge_Bay' => 'CA',
            'America/Campo_Grande' => 'BR', 'America/Cancun' => 'MX', 'America/Caracas' => 'VE', 'America/Catamarca' => 'AR',
            'America/Cayenne' => 'GF', 'America/Cayman' => 'KY', 'America/Chicago' => 'US', 'America/Chihuahua' => 'MX',
            'America/Coral_Harbour' => 'CA', 'America/Cordoba' => 'AR', 'America/Costa_Rica' => 'CR', 'America/Cuiaba' => 'BR',
            'America/Curacao' => 'AN', 'America/Danmarkshavn' => 'GL', 'America/Dawson' => 'CA', 'America/Dawson_Creek' => 'CA',
            'America/Denver' => 'US', 'America/Detroit' => 'US', 'America/Dominica' => 'DM', 'America/Edmonton' => 'CA',
            'America/Eirunepe' => 'BR', 'America/El_Salvador' => 'SV', 'America/Fortaleza' => 'BR', 'America/Glace_Bay' => 'CA',
            'America/Godthab' => 'GL', 'America/Goose_Bay' => 'CA', 'America/Grand_Turk' => 'TC', 'America/Grenada' => 'GD',
            'America/Guadeloupe' => 'GP', 'America/Guatemala' => 'GT', 'America/Guayaquil' => 'EC', 'America/Guyana' => 'GY',
            'America/Halifax' => 'CA', 'America/Havana' => 'CU', 'America/Hermosillo' => 'MX', 'America/Indiana/Knox' => 'US',
            'America/Indiana/Marengo' => 'US', 'America/Indiana/Petersburg' => 'US', 'America/Indiana/Vevay' => 'US',
            'America/Indiana/Vincennes' => 'US', 'America/Indiana/Winamac' => 'US', 'America/Indianapolis' => 'US',
            'America/Inuvik' => 'CA', 'America/Iqaluit' => 'CA', 'America/Jamaica' => 'JM', 'America/Jujuy' => 'AR',
            'America/Juneau' => 'US', 'America/Kentucky/Monticello' => 'US', 'America/La_Paz' => 'BO', 'America/Lima' => 'PE',
            'America/Los_Angeles' => 'US', 'America/Louisville' => 'US', 'America/Maceio' => 'BR', 'America/Managua' => 'NI',
            'America/Manaus' => 'BR', 'America/Martinique' => 'MQ', 'America/Mazatlan' => 'MX', 'America/Mendoza' => 'AR',
            'America/Menominee' => 'US', 'America/Merida' => 'MX', 'America/Mexico_City' => 'MX', 'America/Miquelon' => 'PM',
            'America/Moncton' => 'CA', 'America/Monterrey' => 'MX', 'America/Montevideo' => 'UY', 'America/Montreal' => 'CA',
            'America/Montserrat' => 'MS', 'America/Nassau' => 'BS', 'America/New_York' => 'US', 'America/Nipigon' => 'CA',
            'America/Nome' => 'US', 'America/Noronha' => 'BR', 'America/North_Dakota/Center' => 'US', 'America/North_Dakota/New_Salem' => 'US',
            'America/Panama' => 'PA', 'America/Pangnirtung' => 'CA', 'America/Paramaribo' => 'SR', 'America/Phoenix' => 'US',
            'America/Port_of_Spain' => 'TT', 'America/Port-au-Prince' => 'HT', 'America/Porto_Velho' => 'BR',
            'America/Puerto_Rico' => 'PR', 'America/Rainy_River' => 'CA', 'America/Rankin_Inlet' => 'CA',
            'America/Recife' => 'BR', 'America/Regina' => 'CA', 'America/Rio_Branco' => 'BR', 'America/Santiago' => 'CL',
            'America/Santo_Domingo' => 'DO', 'America/Sao_Paulo' => 'BR', 'America/Scoresbysund' => 'GL',
            'America/Shiprock' => 'US', 'America/St_Johns' => 'CA', 'America/St_Kitts' => 'KN', 'America/St_Lucia' => 'LC',
            'America/St_Thomas' => 'VI', 'America/St_Vincent' => 'VC', 'America/Swift_Current' => 'CA',
            'America/Tegucigalpa' => 'HN', 'America/Thule' => 'GL', 'America/Thunder_Bay' => 'CA', 'America/Tijuana' => 'MX',
            'America/Toronto' => 'CA', 'America/Tortola' => 'VG', 'America/Vancouver' => 'CA', 'America/Whitehorse' => 'CA',
            'America/Winnipeg' => 'CA', 'America/Yakutat' => 'US', 'America/Yellowknife' => 'CA', 'Antarctica/Casey' => 'AQ',
            'Antarctica/Davis' => 'AQ', 'Antarctica/DumontDUrville' => 'AQ', 'Antarctica/Mawson' => 'AQ',
            'Antarctica/McMurdo' => 'AQ', 'Antarctica/Palmer' => 'AQ', 'Antarctica/Rothera' => 'AQ', 'Antarctica/South_Pole' => 'AQ',
            'Antarctica/Syowa' => 'AQ', 'Antarctica/Vostok' => 'AQ', 'Arctic/Longyearbyen' => 'SJ', 'Asia/Aden' => 'YE',
            'Asia/Almaty' => 'KZ', 'Asia/Amman' => 'JO', 'Asia/Anadyr' => 'RU', 'Asia/Aqtau' => 'KZ', 'Asia/Aqtobe' => 'KZ',
            'Asia/Ashgabat' => 'TM', 'Asia/Baghdad' => 'IQ', 'Asia/Bahrain' => 'BH', 'Asia/Baku' => 'AZ',
            'Asia/Bangkok' => 'TH', 'Asia/Beirut' => 'LB', 'Asia/Bishkek' => 'KG', 'Asia/Brunei' => 'BN', 'Asia/Calcutta' => 'IN',
            'Asia/Choibalsan' => 'MN', 'Asia/Chongqing' => 'CN', 'Asia/Colombo' => 'LK', 'Asia/Damascus' => 'SY',
            'Asia/Dhaka' => 'BD', 'Asia/Dili' => 'TL', 'Asia/Dubai' => 'AE', 'Asia/Dushanbe' => 'TJ', 'Asia/Gaza' => 'PS',
            'Asia/Harbin' => 'CN', 'Asia/Hong_Kong' => 'HK', 'Asia/Hovd' => 'MN', 'Asia/Irkutsk' => 'RU', 'Asia/Jakarta' => 'ID',
            'Asia/Jayapura' => 'ID', 'Asia/Jerusalem' => 'IL', 'Asia/Kabul' => 'AF', 'Asia/Kamchatka' => 'RU',
            'Asia/Karachi' => 'PK', 'Asia/Kashgar' => 'CN', 'Asia/Katmandu' => 'NP', 'Asia/Krasnoyarsk' => 'RU',
            'Asia/Kuala_Lumpur' => 'MY', 'Asia/Kuching' => 'MY', 'Asia/Kuwait' => 'KW', 'Asia/Macau' => 'MO',
            'Asia/Magadan' => 'RU', 'Asia/Makassar' => 'ID', 'Asia/Manila' => 'PH', 'Asia/Muscat' => 'OM', 'Asia/Nicosia' => 'CY',
            'Asia/Novosibirsk' => 'RU', 'Asia/Omsk' => 'RU', 'Asia/Oral' => 'KZ', 'Asia/Phnom_Penh' => 'KH',
            'Asia/Pontianak' => 'ID', 'Asia/Pyongyang' => 'KP', 'Asia/Qatar' => 'QA', 'Asia/Qyzylorda' => 'KZ',
            'Asia/Rangoon' => 'MM', 'Asia/Riyadh' => 'SA', 'Asia/Saigon' => 'VN', 'Asia/Sakhalin' => 'RU', 'Asia/Samarkand' => 'UZ',
            'Asia/Seoul' => 'KR', 'Asia/Shanghai' => 'CN', 'Asia/Singapore' => 'SG', 'Asia/Taipei' => 'TW',
            'Asia/Tashkent' => 'UZ', 'Asia/Tbilisi' => 'GE', 'Asia/Tehran' => 'IR', 'Asia/Thimphu' => 'BT',
            'Asia/Tokyo' => 'JP', 'Asia/Ulaanbaatar' => 'MN', 'Asia/Urumqi' => 'CN', 'Asia/Vientiane' => 'LA',
            'Asia/Vladivostok' => 'RU', 'Asia/Yakutsk' => 'RU', 'Asia/Yekaterinburg' => 'RU', 'Asia/Yerevan' => 'AM',
            'Atlantic/Azores' => 'PT', 'Atlantic/Bermuda' => 'BM', 'Atlantic/Canary' => 'ES', 'Atlantic/Cape_Verde' => 'CV',
            'Atlantic/Faeroe' => 'FO', 'Atlantic/Jan_Mayen' => 'SJ', 'Atlantic/Madeira' => 'PT', 'Atlantic/Reykjavik' => 'IS',
            'Atlantic/South_Georgia' => 'GS', 'Atlantic/St_Helena' => 'SH', 'Atlantic/Stanley' => 'FK', 'Australia/Adelaide' => 'AU',
            'Australia/Brisbane' => 'AU', 'Australia/Broken_Hill' => 'AU', 'Australia/Currie' => 'AU', 'Australia/Darwin' => 'AU',
            'Australia/Eucla' => 'AU', 'Australia/Hobart' => 'AU', 'Australia/Lindeman' => 'AU', 'Australia/Lord_Howe' => 'AU',
            'Australia/Melbourne' => 'AU', 'Australia/Perth' => 'AU', 'Australia/Sydney' => 'AU', 'Etc/GMT' => '001',
            'Etc/GMT-1' => '001', 'Etc/GMT-2' => '001', 'Etc/GMT-3' => '001', 'Etc/GMT-4' => '001', 'Etc/GMT-5' => '001',
            'Etc/GMT-6' => '001', 'Etc/GMT-7' => '001', 'Etc/GMT-8' => '001', 'Etc/GMT-9' => '001', 'Etc/GMT-10' => '001',
            'Etc/GMT-11' => '001', 'Etc/GMT-12' => '001', 'Etc/GMT-13' => '001', 'Etc/GMT-14' => '001', 'Etc/GMT+1' => '001',
            'Etc/GMT+2' => '001', 'Etc/GMT+3' => '001', 'Etc/GMT+4' => '001', 'Etc/GMT+5' => '001', 'Etc/GMT+6' => '001',
            'Etc/GMT+7' => '001', 'Etc/GMT+8' => '001', 'Etc/GMT+9' => '001', 'Etc/GMT+10' => '001', 'Etc/GMT+11' => '001',
            'Etc/GMT+12' => '001', 'Etc/Unknown' => '001', 'Europe/Amsterdam' => 'NL', 'Europe/Andorra' => 'AD',
            'Europe/Athens' => 'GR', 'Europe/Belgrade' => 'RS', 'Europe/Berlin' => 'DE', 'Europe/Bratislava' => 'SK',
            'Europe/Brussels' => 'BE', 'Europe/Bucharest' => 'RO', 'Europe/Budapest' => 'HU', 'Europe/Chisinau' => 'MD',
            'Europe/Copenhagen' => 'DK', 'Europe/Dublin' => 'IE', 'Europe/Gibraltar' => 'GI', 'Europe/Guernsey' => 'GG',
            'Europe/Helsinki' => 'FI', 'Europe/Isle_of_Man' => 'IM', 'Europe/Istanbul' => 'TR', 'Europe/Jersey' => 'JE',
            'Europe/Kaliningrad' => 'RU', 'Europe/Kiev' => 'UA', 'Europe/Lisbon' => 'PT', 'Europe/Ljubljana' => 'SI',
            'Europe/London' => 'GB', 'Europe/Luxembourg' => 'LU', 'Europe/Madrid' => 'ES', 'Europe/Malta' => 'MT',
            'Europe/Mariehamn' => 'AX', 'Europe/Minsk' => 'BY', 'Europe/Monaco' => 'MC', 'Europe/Moscow' => 'RU',
            'Europe/Oslo' => 'NO', 'Europe/Paris' => 'FR', 'Europe/Podgorica' => 'ME', 'Europe/Prague' => 'CZ',
            'Europe/Riga' => 'LV', 'Europe/Rome' => 'IT', 'Europe/Samara' => 'RU', 'Europe/San_Marino' => 'SM',
            'Europe/Sarajevo' => 'BA', 'Europe/Simferopol' => 'UA', 'Europe/Skopje' => 'MK', 'Europe/Sofia' => 'BG',
            'Europe/Stockholm' => 'SE', 'Europe/Tallinn' => 'EE', 'Europe/Tirane' => 'AL', 'Europe/Uzhgorod' => 'UA',
            'Europe/Vaduz' => 'LI', 'Europe/Vatican' => 'VA', 'Europe/Vienna' => 'AT', 'Europe/Vilnius' => 'LT',
            'Europe/Volgograd' => 'RU', 'Europe/Warsaw' => 'PL', 'Europe/Zagreb' => 'HR', 'Europe/Zaporozhye' => 'UA',
            'Europe/Zurich' => 'CH', 'Indian/Antananarivo' => 'MG', 'Indian/Chagos' => 'IO', 'Indian/Christmas' => 'CX',
            'Indian/Cocos' => 'CC', 'Indian/Comoro' => 'KM', 'Indian/Kerguelen' => 'TF', 'Indian/Mahe' => 'SC',
            'Indian/Maldives' => 'MV', 'Indian/Mauritius' => 'MU', 'Indian/Mayotte' => 'YT', 'Indian/Reunion' => 'RE',
            'Pacific/Apia' => 'WS', 'Pacific/Auckland' => 'NZ', 'Pacific/Chatham' => 'NZ', 'Pacific/Easter' => 'CL',
            'Pacific/Efate' => 'VU', 'Pacific/Enderbury' => 'KI', 'Pacific/Fakaofo' => 'TK', 'Pacific/Fiji' => 'FJ',
            'Pacific/Funafuti' => 'TV', 'Pacific/Galapagos' => 'EC', 'Pacific/Gambier' => 'PF', 'Pacific/Guadalcanal' => 'SB',
            'Pacific/Guam' => 'GU', 'Pacific/Honolulu' => 'US', 'Pacific/Johnston' => 'UM', 'Pacific/Kiritimati' => 'KI',
            'Pacific/Kosrae' => 'FM', 'Pacific/Kwajalein' => 'MH', 'Pacific/Majuro' => 'MH', 'Pacific/Marquesas' => 'PF',
            'Pacific/Midway' => 'UM', 'Pacific/Nauru' => 'NR', 'Pacific/Niue' => 'NU', 'Pacific/Norfolk' => 'NF',
            'Pacific/Noumea' => 'NC', 'Pacific/Pago_Pago' => 'AS', 'Pacific/Palau' => 'PW', 'Pacific/Pitcairn' => 'PN',
            'Pacific/Ponape' => 'FM', 'Pacific/Port_Moresby' => 'PG', 'Pacific/Rarotonga' => 'CK', 'Pacific/Saipan' => 'MP',
            'Pacific/Tahiti' => 'PF', 'Pacific/Tarawa' => 'KI', 'Pacific/Tongatapu' => 'TO', 'Pacific/Truk' => 'FM',
            'Pacific/Wake' => 'UM', 'Pacific/Wallis' => 'WF', 'America/Indiana/Tell_City' => 'US', 'America/Resolute' => 'CA');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'territorytotimezone', 'Pacific/Fiji');
        $this->assertEquals("FJ", $value);
    }

    /**
     * test for reading timezonetoterritory from locale
     * expected array
     */
    public function testTimezoneToTerritory()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'timezonetoterritory');
        $result = array('CI' => 'Africa/Abidjan', 'GH' => 'Africa/Accra', 'ET' => 'Africa/Addis_Ababa',
            'DZ' => 'Africa/Algiers', 'ER' => 'Africa/Asmera', 'ML' => 'Africa/Bamako', 'CF' => 'Africa/Bangui',
            'GM' => 'Africa/Banjul', 'GW' => 'Africa/Bissau', 'MW' => 'Africa/Blantyre', 'CG' => 'Africa/Brazzaville',
            'BI' => 'Africa/Bujumbura', 'EG' => 'Africa/Cairo', 'MA' => 'Africa/Casablanca', 'ES' => 'Africa/Ceuta',
            'GN' => 'Africa/Conakry', 'SN' => 'Africa/Dakar', 'TZ' => 'Africa/Dar_es_Salaam', 'DJ' => 'Africa/Djibouti',
            'CM' => 'Africa/Douala', 'EH' => 'Africa/El_Aaiun', 'SL' => 'Africa/Freetown', 'BW' => 'Africa/Gaborone',
            'ZW' => 'Africa/Harare', 'ZA' => 'Africa/Johannesburg', 'UG' => 'Africa/Kampala', 'SD' => 'Africa/Khartoum',
            'RW' => 'Africa/Kigali', 'CD' => 'Africa/Kinshasa', 'NG' => 'Africa/Lagos', 'GA' => 'Africa/Libreville',
            'TG' => 'Africa/Lome', 'AO' => 'Africa/Luanda', 'ZM' => 'Africa/Lusaka', 'GQ' => 'Africa/Malabo',
            'MZ' => 'Africa/Maputo', 'LS' => 'Africa/Maseru', 'SZ' => 'Africa/Mbabane', 'SO' => 'Africa/Mogadishu',
            'LR' => 'Africa/Monrovia', 'KE' => 'Africa/Nairobi', 'TD' => 'Africa/Ndjamena', 'NE' => 'Africa/Niamey',
            'MR' => 'Africa/Nouakchott', 'BF' => 'Africa/Ouagadougou', 'BJ' => 'Africa/Porto-Novo', 'ST' => 'Africa/Sao_Tome',
            'LY' => 'Africa/Tripoli', 'TN' => 'Africa/Tunis', 'NA' => 'Africa/Windhoek', 'US' => 'America/Adak',
            'AI' => 'America/Anguilla', 'AG' => 'America/Antigua', 'BR' => 'America/Araguaina', 'AR' => 'America/Argentina/La_Rioja',
            'AW' => 'America/Aruba', 'PY' => 'America/Asuncion', 'BB' => 'America/Barbados', 'BZ' => 'America/Belize',
            'CA' => 'America/Blanc-Sablon', 'CO' => 'America/Bogota', 'MX' => 'America/Cancun', 'VE' => 'America/Caracas',
            'GF' => 'America/Cayenne', 'KY' => 'America/Cayman', 'CR' => 'America/Costa_Rica', 'AN' => 'America/Curacao',
            'GL' => 'America/Danmarkshavn', 'DM' => 'America/Dominica', 'SV' => 'America/El_Salvador', 'TC' => 'America/Grand_Turk',
            'GD' => 'America/Grenada', 'GP' => 'America/Guadeloupe', 'GT' => 'America/Guatemala', 'EC' => 'America/Guayaquil',
            'GY' => 'America/Guyana', 'CU' => 'America/Havana', 'JM' => 'America/Jamaica', 'BO' => 'America/La_Paz',
            'PE' => 'America/Lima', 'NI' => 'America/Managua', 'MQ' => 'America/Martinique', 'PM' => 'America/Miquelon',
            'UY' => 'America/Montevideo', 'MS' => 'America/Montserrat', 'BS' => 'America/Nassau', 'PA' => 'America/Panama',
            'SR' => 'America/Paramaribo', 'TT' => 'America/Port_of_Spain', 'HT' => 'America/Port-au-Prince',
            'PR' => 'America/Puerto_Rico', 'CL' => 'America/Santiago', 'DO' => 'America/Santo_Domingo', 'KN' => 'America/St_Kitts',
            'LC' => 'America/St_Lucia', 'VI' => 'America/St_Thomas', 'VC' => 'America/St_Vincent', 'HN' => 'America/Tegucigalpa',
            'VG' => 'America/Tortola', 'AQ' => 'Antarctica/Casey', 'SJ' => 'Arctic/Longyearbyen', 'YE' => 'Asia/Aden',
            'KZ' => 'Asia/Almaty', 'JO' => 'Asia/Amman', 'RU' => 'Asia/Anadyr', 'TM' => 'Asia/Ashgabat', 'IQ' => 'Asia/Baghdad',
            'BH' => 'Asia/Bahrain', 'AZ' => 'Asia/Baku', 'TH' => 'Asia/Bangkok', 'LB' => 'Asia/Beirut', 'KG' => 'Asia/Bishkek',
            'BN' => 'Asia/Brunei', 'IN' => 'Asia/Calcutta', 'MN' => 'Asia/Choibalsan', 'CN' => 'Asia/Chongqing',
            'LK' => 'Asia/Colombo', 'SY' => 'Asia/Damascus', 'BD' => 'Asia/Dhaka', 'TL' => 'Asia/Dili', 'AE' => 'Asia/Dubai',
            'TJ' => 'Asia/Dushanbe', 'PS' => 'Asia/Gaza', 'HK' => 'Asia/Hong_Kong', 'ID' => 'Asia/Jakarta', 'IL' => 'Asia/Jerusalem',
            'AF' => 'Asia/Kabul', 'PK' => 'Asia/Karachi', 'NP' => 'Asia/Katmandu', 'MY' => 'Asia/Kuala_Lumpur',
            'KW' => 'Asia/Kuwait', 'MO' => 'Asia/Macau', 'PH' => 'Asia/Manila', 'OM' => 'Asia/Muscat', 'CY' => 'Asia/Nicosia',
            'KH' => 'Asia/Phnom_Penh', 'KP' => 'Asia/Pyongyang', 'QA' => 'Asia/Qatar', 'MM' => 'Asia/Rangoon',
            'SA' => 'Asia/Riyadh', 'VN' => 'Asia/Saigon', 'UZ' => 'Asia/Samarkand', 'KR' => 'Asia/Seoul', 'SG' => 'Asia/Singapore',
            'TW' => 'Asia/Taipei', 'GE' => 'Asia/Tbilisi', 'IR' => 'Asia/Tehran', 'BT' => 'Asia/Thimphu', 'JP' => 'Asia/Tokyo',
            'LA' => 'Asia/Vientiane', 'AM' => 'Asia/Yerevan', 'PT' => 'Atlantic/Azores', 'BM' => 'Atlantic/Bermuda',
            'CV' => 'Atlantic/Cape_Verde', 'FO' => 'Atlantic/Faeroe', 'IS' => 'Atlantic/Reykjavik', 'GS' => 'Atlantic/South_Georgia',
            'SH' => 'Atlantic/St_Helena', 'FK' => 'Atlantic/Stanley', 'AU' => 'Australia/Adelaide', '001' => 'Etc/GMT',
            'NL' => 'Europe/Amsterdam', 'AD' => 'Europe/Andorra', 'GR' => 'Europe/Athens', 'RS' => 'Europe/Belgrade',
            'DE' => 'Europe/Berlin', 'SK' => 'Europe/Bratislava', 'BE' => 'Europe/Brussels', 'RO' => 'Europe/Bucharest',
            'HU' => 'Europe/Budapest', 'MD' => 'Europe/Chisinau', 'DK' => 'Europe/Copenhagen', 'IE' => 'Europe/Dublin',
            'GI' => 'Europe/Gibraltar', 'GG' => 'Europe/Guernsey', 'FI' => 'Europe/Helsinki', 'IM' => 'Europe/Isle_of_Man',
            'TR' => 'Europe/Istanbul', 'JE' => 'Europe/Jersey', 'UA' => 'Europe/Kiev', 'SI' => 'Europe/Ljubljana',
            'GB' => 'Europe/London', 'LU' => 'Europe/Luxembourg', 'MT' => 'Europe/Malta', 'AX' => 'Europe/Mariehamn',
            'BY' => 'Europe/Minsk', 'MC' => 'Europe/Monaco', 'NO' => 'Europe/Oslo', 'FR' => 'Europe/Paris', 'ME' => 'Europe/Podgorica',
            'CZ' => 'Europe/Prague', 'LV' => 'Europe/Riga', 'IT' => 'Europe/Rome', 'SM' => 'Europe/San_Marino',
            'BA' => 'Europe/Sarajevo', 'MK' => 'Europe/Skopje', 'BG' => 'Europe/Sofia', 'SE' => 'Europe/Stockholm',
            'EE' => 'Europe/Tallinn', 'AL' => 'Europe/Tirane', 'LI' => 'Europe/Vaduz', 'VA' => 'Europe/Vatican',
            'AT' => 'Europe/Vienna', 'LT' => 'Europe/Vilnius', 'PL' => 'Europe/Warsaw', 'HR' => 'Europe/Zagreb',
            'CH' => 'Europe/Zurich', 'MG' => 'Indian/Antananarivo', 'IO' => 'Indian/Chagos', 'CX' => 'Indian/Christmas',
            'CC' => 'Indian/Cocos', 'KM' => 'Indian/Comoro', 'TF' => 'Indian/Kerguelen', 'SC' => 'Indian/Mahe',
            'MV' => 'Indian/Maldives', 'MU' => 'Indian/Mauritius', 'YT' => 'Indian/Mayotte', 'RE' => 'Indian/Reunion',
            'WS' => 'Pacific/Apia', 'NZ' => 'Pacific/Auckland', 'VU' => 'Pacific/Efate', 'KI' => 'Pacific/Enderbury',
            'TK' => 'Pacific/Fakaofo', 'FJ' => 'Pacific/Fiji', 'TV' => 'Pacific/Funafuti', 'PF' => 'Pacific/Gambier',
            'SB' => 'Pacific/Guadalcanal', 'GU' => 'Pacific/Guam', 'UM' => 'Pacific/Johnston', 'FM' => 'Pacific/Kosrae',
            'MH' => 'Pacific/Kwajalein', 'NR' => 'Pacific/Nauru', 'NU' => 'Pacific/Niue', 'NF' => 'Pacific/Norfolk',
            'NC' => 'Pacific/Noumea', 'AS' => 'Pacific/Pago_Pago', 'PW' => 'Pacific/Palau', 'PN' => 'Pacific/Pitcairn',
            'PG' => 'Pacific/Port_Moresby', 'CK' => 'Pacific/Rarotonga', 'MP' => 'Pacific/Saipan', 'TO' => 'Pacific/Tongatapu',
            'WF' => 'Pacific/Wallis');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'timezonetoterritory', 'FJ');
        $this->assertEquals("Pacific/Fiji", $value);
    }

    /**
     * test for reading citytotimezone from locale
     * expected array
     */
    public function testCityToTimezone()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'citytotimezone');
        $result = array('Etc/Unknown' => 'Unbekannt', 'Europe/Tirane' => 'Tirana', 'Asia/Yerevan' => 'Erivan',
            'America/Curacao' => 'Curaçao', 'Antarctica/South_Pole' => 'Südpol', 'Antarctica/Vostok' => 'Wostok',
            'Antarctica/DumontDUrville' => "Dumont D'Urville", 'Europe/Vienna' => 'Wien', 'Europe/Brussels' => 'Brüssel',
            'Africa/Ouagadougou' => 'Wagadugu', 'Atlantic/Bermuda' => 'Bermudas', 'America/St_Johns' => "St. John's",
            'Europe/Zurich' => 'Zürich', 'Pacific/Easter' => 'Osterinsel', 'America/Havana' => 'Havanna',
            'Atlantic/Cape_Verde' => 'Kap Verde', 'Indian/Christmas' => 'Weihnachts-Inseln', 'Asia/Nicosia' => 'Nikosia',
            'Africa/Djibouti' => 'Dschibuti', 'Europe/Copenhagen' => 'Kopenhagen', 'Africa/Algiers' => 'Algier',
            'Africa/Cairo' => 'Kairo', 'Africa/El_Aaiun' => 'El Aaiún', 'Atlantic/Canary' => 'Kanaren',
            'Africa/Addis_Ababa' => 'Addis Abeba', 'Pacific/Fiji' => 'Fidschi', 'Atlantic/Faeroe' => 'Färöer',
            'Asia/Tbilisi' => 'Tiflis', 'Africa/Accra' => 'Akkra',
            'Europe/Athens' => 'Athen', 'Atlantic/South_Georgia' => 'Süd-Georgien', 'Asia/Hong_Kong' => 'Hongkong',
            'Asia/Baghdad' => 'Bagdad', 'Asia/Tehran' => 'Teheran', 'Europe/Rome' => 'Rom', 'America/Jamaica' => 'Jamaika',
            'Asia/Tokyo' => 'Tokio', 'Asia/Bishkek' => 'Bischkek', 'Indian/Comoro' => 'Komoren', 'America/St_Kitts' => 'St. Kitts',
            'Asia/Pyongyang' => 'Pjöngjang', 'America/Cayman' => 'Kaimaninseln', 'Asia/Aqtobe' => 'Aktobe',
            'America/St_Lucia' => 'St. Lucia', 'Europe/Vilnius' => 'Wilna', 'Europe/Luxembourg' => 'Luxemburg',
            'Africa/Tripoli' => 'Tripolis', 'Europe/Chisinau' => 'Kischinau',
            'Asia/Macau' => 'Macao', 'Indian/Maldives' => 'Malediven', 'America/Mexico_City' => 'Mexiko-Stadt',
            'Africa/Niamey' => 'Niger', 'Asia/Muscat' => 'Muskat', 'Europe/Warsaw' => 'Warschau',
            'Atlantic/Azores' => 'Azoren', 'Europe/Lisbon' => 'Lissabon', 'America/Asuncion' => 'Asunción',
            'Asia/Qatar' => 'Katar', 'Indian/Reunion' => 'Réunion', 'Europe/Bucharest' => 'Bukarest',
            'Europe/Moscow' => 'Moskau', 'Asia/Yekaterinburg' => 'Jekaterinburg', 'Asia/Novosibirsk' => 'Nowosibirsk',
            'Asia/Krasnoyarsk' => 'Krasnojarsk', 'Asia/Yakutsk' => 'Jakutsk', 'Asia/Vladivostok' => 'Wladiwostok',
            'Asia/Sakhalin' => 'Sachalin', 'Asia/Kamchatka' => 'Kamtschatka', 'Asia/Riyadh' => 'Riad',
            'Africa/Khartoum' => 'Khartum', 'Asia/Singapore' => 'Singapur', 'Atlantic/St_Helena' => 'St. Helena',
            'Africa/Mogadishu' => 'Mogadischu', 'Africa/Sao_Tome' => 'São Tomé', 'America/El_Salvador' => 'Salvador',
            'Asia/Damascus' => 'Damaskus', 'Asia/Dushanbe' => 'Duschanbe', 'America/Port_of_Spain' => 'Port-of-Spain',
            'Asia/Taipei' => 'Taipeh', 'Africa/Dar_es_Salaam' => 'Daressalam', 'Europe/Uzhgorod' => 'Uschgorod',
            'Europe/Kiev' => 'Kiew', 'Europe/Zaporozhye' => 'Saporischja',
            'Asia/Tashkent' => 'Taschkent', 'America/St_Vincent' => 'St. Vincent', 'America/St_Thomas' => 'St. Thomas');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'citytotimezone', 'Pacific/Fiji');
        $this->assertEquals("Fidschi", $value);
    }

    /**
     * test for reading timezonetocity from locale
     * expected array
     */
    public function testTimezoneToCity()
    {
        $value = Zend_Locale_Data::getList('de_AT', 'timezonetocity');
        $result = array('Unbekannt' => 'Etc/Unknown', 'Tirana' => 'Europe/Tirane', 'Erivan' => 'Asia/Yerevan',
            'Curaçao' => 'America/Curacao', 'Südpol' => 'Antarctica/South_Pole', 'Wostok' => 'Antarctica/Vostok',
            "Dumont D'Urville" => 'Antarctica/DumontDUrville', 'Wien' => 'Europe/Vienna', 'Brüssel' => 'Europe/Brussels',
            'Wagadugu' => 'Africa/Ouagadougou', 'Bermudas' => 'Atlantic/Bermuda', "St. John's" => 'America/St_Johns',
            'Zürich' => 'Europe/Zurich', 'Osterinsel' => 'Pacific/Easter', 'Havanna' => 'America/Havana',
            'Kap Verde' => 'Atlantic/Cape_Verde', 'Weihnachts-Inseln' => 'Indian/Christmas', 'Nikosia' => 'Asia/Nicosia',
            'Dschibuti' => 'Africa/Djibouti', 'Kopenhagen' => 'Europe/Copenhagen', 'Algier' => 'Africa/Algiers',
            'Kairo' => 'Africa/Cairo', 'El Aaiún' => 'Africa/El_Aaiun', 'Kanaren' => 'Atlantic/Canary',
            'Addis Abeba' => 'Africa/Addis_Ababa', 'Fidschi' => 'Pacific/Fiji', 'Färöer' => 'Atlantic/Faeroe',
            'Tiflis' => 'Asia/Tbilisi', 'Akkra' => 'Africa/Accra',
            'Athen' => 'Europe/Athens', 'Süd-Georgien' => 'Atlantic/South_Georgia', 'Hongkong' => 'Asia/Hong_Kong',
            'Bagdad' => 'Asia/Baghdad', 'Teheran' => 'Asia/Tehran', 'Rom' => 'Europe/Rome', 'Jamaika' => 'America/Jamaica',
            'Tokio' => 'Asia/Tokyo', 'Bischkek' => 'Asia/Bishkek', 'Komoren' => 'Indian/Comoro', 'St. Kitts' => 'America/St_Kitts',
            'Pjöngjang' => 'Asia/Pyongyang', 'Kaimaninseln' => 'America/Cayman', 'Aktobe' => 'Asia/Aqtobe',
            'St. Lucia' => 'America/St_Lucia', 'Wilna' => 'Europe/Vilnius', 'Luxemburg' => 'Europe/Luxembourg',
            'Tripolis' => 'Africa/Tripoli', 'Kischinau' => 'Europe/Chisinau',
            'Macao' => 'Asia/Macau', 'Malediven' => 'Indian/Maldives', 'Mexiko-Stadt' => 'America/Mexico_City',
            'Niger' => 'Africa/Niamey', 'Muskat' => 'Asia/Muscat', 'Warschau' => 'Europe/Warsaw', 'Azoren' => 'Atlantic/Azores',
            'Lissabon' => 'Europe/Lisbon', 'Asunción' => 'America/Asuncion', 'Katar' => 'Asia/Qatar',
            'Réunion' => 'Indian/Reunion', 'Bukarest' => 'Europe/Bucharest', 'Moskau' => 'Europe/Moscow',
            'Jekaterinburg' => 'Asia/Yekaterinburg', 'Nowosibirsk' => 'Asia/Novosibirsk', 'Krasnojarsk' => 'Asia/Krasnoyarsk',
            'Jakutsk' => 'Asia/Yakutsk', 'Wladiwostok' => 'Asia/Vladivostok', 'Sachalin' => 'Asia/Sakhalin',
            'Kamtschatka' => 'Asia/Kamchatka', 'Riad' => 'Asia/Riyadh', 'Khartum' => 'Africa/Khartoum',
            'Singapur' => 'Asia/Singapore', 'St. Helena' => 'Atlantic/St_Helena', 'Mogadischu' => 'Africa/Mogadishu',
            'São Tomé' => 'Africa/Sao_Tome', 'Salvador' => 'America/El_Salvador', 'Damaskus' => 'Asia/Damascus',
            'Duschanbe' => 'Asia/Dushanbe', 'Port-of-Spain' => 'America/Port_of_Spain', 'Taipeh' => 'Asia/Taipei',
            'Daressalam' => 'Africa/Dar_es_Salaam', 'Uschgorod' => 'Europe/Uzhgorod', 'Kiew' => 'Europe/Kiev',
            'Saporischja' => 'Europe/Zaporozhye', 'Taschkent' => 'Asia/Tashkent',
            'St. Vincent' => 'America/St_Vincent', 'St. Thomas' => 'America/St_Thomas');
        $this->assertEquals($result, $value);

        $value = Zend_Locale_Data::getContent('de_AT', 'timezonetocity', 'Fidschi');
        $this->assertEquals("Pacific/Fiji", $value);
    }
}