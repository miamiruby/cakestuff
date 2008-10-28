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
 * @category  Zend
 * @package   Zend_Locale
 * @copyright Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 * @version   $Id: Locale.php 10081 2008-07-14 13:05:48Z thomas $
 */

/**
 * Base class for localization
 *
 * @category  Zend
 * @package   Zend_Locale
 * @copyright Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Locale
{
    /**
     * Class wide Locale Constants
     *
     * @var array $_localeData
     */
    private static $_localeData = array(
        'root'  => true, 'aa_DJ' => true, 'aa_ER' => true, 'aa_ET' => true, 'aa'    => true,
        'af_NA' => true, 'af_ZA' => true, 'af'    => true, 'ak_GH' => true, 'ak'    => true,
        'am_ET' => true, 'am'    => true, 'ar_AE' => true, 'ar_BH' => true, 'ar_DZ' => true,
        'ar_EG' => true, 'ar_IQ' => true, 'ar_JO' => true, 'ar_KW' => true, 'ar_LB' => true,
        'ar_LY' => true, 'ar_MA' => true, 'ar_OM' => true, 'ar_QA' => true, 'ar_SA' => true,
        'ar_SD' => true, 'ar_SY' => true, 'ar_TN' => true, 'ar_YE' => true, 'ar'    => true,
        'as_IN' => true, 'as'    => true, 'az_AZ' => true, 'az'    => true, 'be_BY' => true,
        'be'    => true, 'bg_BG' => true, 'bg'    => true, 'bn_BD' => true, 'bn_IN' => true,
        'bn'    => true, 'bo_CN' => true, 'bo_IN' => true, 'bo'    => true, 'bs_BA' => true,
        'bs'    => true, 'byn_ER'=> true, 'byn'   => true, 'ca_ES' => true, 'ca'    => true,
        'cch_NG'=> true, 'cch'   => true, 'cop_EG'=> true, 'cop_US'=> true, 'cop'   => true,
        'cs_CZ' => true, 'cs'    => true, 'cy_GB' => true, 'cy'    => true, 'da_DK' => true,
        'da'    => true, 'de_AT' => true, 'de_BE' => true, 'de_CH' => true, 'de_DE' => true,
        'de_LI' => true, 'de_LU' => true, 'de'    => true, 'dv_MV' => true, 'dv'    => true,
        'dz_BT' => true, 'dz'    => true, 'ee_GH' => true, 'ee_TG' => true, 'ee'    => true,
        'el_CY' => true, 'el_GR' => true, 'el'    => true, 'en_AS' => true, 'en_AU' => true,
        'en_BE' => true, 'en_BW' => true, 'en_BZ' => true, 'en_CA' => true, 'en_GB' => true,
        'en_GU' => true, 'en_HK' => true, 'en_IE' => true, 'en_IN' => true, 'en_JM' => true,
        'en_MH' => true, 'en_MP' => true, 'en_MT' => true, 'en_NZ' => true, 'en_PH' => true,
        'en_PK' => true, 'en_SG' => true, 'en_TT' => true, 'en_UM' => true, 'en_US' => true,
        'en_VI' => true, 'en_ZA' => true, 'en_ZW' => true, 'en'    => true, 'eo'    => true,
        'es_AR' => true, 'es_BO' => true, 'es_CL' => true, 'es_CO' => true, 'es_CR' => true,
        'es_DO' => true, 'es_EC' => true, 'es_ES' => true, 'es_GT' => true, 'es_HN' => true,
        'es_MX' => true, 'es_NI' => true, 'es_PA' => true, 'es_PE' => true, 'es_PR' => true,
        'es_PY' => true, 'es_SV' => true, 'es_US' => true, 'es_UY' => true, 'es_VE' => true,
        'es'    => true, 'et_EE' => true, 'et'    => true, 'eu_ES' => true, 'eu'    => true,
        'fa_AF' => true, 'fa_IR' => true, 'fa'    => true, 'fi_FI' => true, 'fi'    => true,
        'fil'   => true, 'fo_FO' => true, 'fo'    => true, 'fr_BE' => true, 'fr_CA' => true,
        'fr_CH' => true, 'fr_FR' => true, 'fr_LU' => true, 'fr_MC' => true, 'fr'    => true,
        'fur_IT'=> true, 'fur'   => true, 'ga_IE' => true, 'ga'    => true, 'gaa_GH'=> true,
        'gaa'   => true, 'gez_ER'=> true, 'gez_ET'=> true, 'gez'   => true, 'gl_ES' => true,
        'gl'    => true, 'gu_IN' => true, 'gu'    => true, 'gv_GB' => true, 'gv'    => true,
        'ha_GH' => true, 'ha_NE' => true, 'ha_NG' => true, 'ha'    => true, 'haw_US'=> true,
        'haw'   => true, 'he_IL' => true, 'he'    => true, 'hi_IN' => true, 'hi'    => true,
        'hr_HR' => true, 'hr'    => true, 'hu_HU' => true, 'hu'    => true, 'hy_AM' => true,
        'hy'    => true, 'ia'    => true, 'id_ID' => true, 'id'    => true, 'ig_NG' => true,
        'ig'    => true, 'ii_CN' => true, 'ii'    => true, 'is_IS' => true, 'is'    => true,
        'it_CH' => true, 'it_IT' => true, 'it'    => true, 'iu'    => true, 'ja_JP' => true,
        'ja'    => true, 'ka_GE' => true, 'ka'    => true, 'kaj_NG'=> true, 'kaj'   => true,
        'kam_KE'=> true, 'kam'   => true, 'kcg_NG'=> true, 'kcg'   => true, 'kfo_NG'=> true,
        'kfo'   => true, 'kk_KZ' => true, 'kk'    => true, 'kl_GL' => true, 'kl'    => true,
        'km_KH' => true, 'km'    => true, 'kn_IN' => true, 'kn'    => true, 'ko_KR' => true,
        'ko'    => true, 'kok_IN'=> true, 'kok'   => true, 'kpe_GN'=> true, 'kpe_LR'=> true,
        'kpe'   => true, 'ku_IQ' => true, 'ku_IR' => true, 'ku_SY' => true, 'ku_TR' => true,
        'ku'    => true, 'kw_GB' => true, 'kw'    => true, 'ky_KG' => true, 'ky'    => true,
        'ln_CD' => true, 'ln_CG' => true, 'ln'    => true, 'lo_LA' => true, 'lo'    => true,
        'lt_LT' => true, 'lt'    => true, 'lv_LV' => true, 'lv'    => true, 'mk_MK' => true,
        'mk'    => true, 'ml_IN' => true, 'ml'    => true, 'mn_MN' => true, 'mn'    => true,
        'mr_IN' => true, 'mr'    => true, 'ms_BN' => true, 'ms_MY' => true, 'ms'    => true,
        'mt_MT' => true, 'mt'    => true, 'my_MM' => true, 'my'    => true, 'nb_NO' => true,
        'nb'    => true, 'ne_NP' => true, 'ne'    => true, 'nl_BE' => true, 'nl_NL' => true,
        'nl'    => true, 'nn_NO' => true, 'nn'    => true, 'nr_ZA' => true, 'nr'    => true,
        'nso_ZA'=> true, 'nso'   => true, 'ny_MW' => true, 'ny'    => true, 'om_ET' => true,
        'om_KE' => true, 'om'    => true, 'or_IN' => true, 'or'    => true, 'pa_IN' => true,
        'pa_PK' => true, 'pa'    => true, 'pl_PL' => true, 'pl'    => true, 'ps_AF' => true,
        'ps'    => true, 'pt_BR' => true, 'pt_PT' => true, 'pt'    => true, 'ro_RO' => true,
        'ro'    => true, 'ru_RU' => true, 'ru_UA' => true, 'ru'    => true, 'rw_RW' => true,
        'rw'    => true, 'sa_IN' => true, 'sa'    => true, 'se_FI' => true, 'se_NO' => true,
        'se'    => true, 'sh_BA' => true, 'sh_CS' => true, 'sh_YU' => true, 'sh'    => true,
        'sid_ET'=> true, 'sid'   => true, 'sk_SK' => true, 'sk'    => true, 'sl_SI' => true,
        'sl'    => true, 'so_DJ' => true, 'so_ET' => true, 'so_KE' => true, 'so_SO' => true,
        'so'    => true, 'sq_AL' => true, 'sq'    => true, 'sr_BA' => true, 'sr_CS' => true,
        'sr_ME' => true, 'sr_RS' => true, 'sr_YU' => true, 'sr'    => true, 'ss_ZA' => true,
        'ss'    => true, 'ssy'   => true, 'st_ZA' => true, 'st'    => true, 'sv_FI' => true,
        'sv_SE' => true, 'sv'    => true, 'sw_KE' => true, 'sw_TZ' => true, 'sw'    => true,
        'syr_SY'=> true, 'syr'   => true, 'ta_IN' => true, 'ta'    => true, 'te_IN' => true,
        'te'    => true, 'tg_TJ' => true, 'tg'    => true, 'th_TH' => true, 'th'    => true,
        'ti_ER' => true, 'ti_ET' => true, 'ti'    => true, 'tig_ER'=> true, 'tig'   => true,
        'tn_ZA' => true, 'tn'    => true, 'to_TO' => true, 'to'    => true, 'tr_TR' => true,
        'tr'    => true, 'ts_ZA' => true, 'ts'    => true, 'tt_RU' => true, 'tt'    => true,
        'ug'    => true, 'uk_UA' => true, 'uk'    => true, 'und_ZZ'=> true, 'und'   => true,
        'ur_IN' => true, 'ur_PK' => true, 'ur'    => true, 'uz_AF' => true, 'uz_UZ' => true,
        'uz'    => true, 've_ZA' => true, 've'    => true, 'vi_VN' => true, 'vi'    => true,
        'wal_ET'=> true, 'wal'   => true, 'wo_SN' => true, 'wo'    => true, 'xh_ZA' => true,
        'xh'    => true, 'yo_NG' => true, 'yo'    => true, 'zh_CN' => true, 'zh_HK' => true,
        'zh_MO' => true, 'zh_SG' => true, 'zh_TW' => true, 'zh'    => true, 'zu_ZA' => true,
        'zu'    => true, 'auto'  => false, 'browser' => false, 'environment' => false
    );

    /**
     * Autosearch constants
     */
    const BROWSER     = 'browser';
    const ENVIRONMENT = 'environment';
    const FRAMEWORK   = 'framework';

    /**
     * Actual set locale
     *
     * @var string Locale
     */
    protected $_locale;

    /**
     * Automatic detected locale
     *
     * @var string Locales
     */
    protected static $_auto;

    /**
     * Browser detected locale
     *
     * @var string Locales
     */
    protected static $_browser;

    /**
     * Environment detected locale
     *
     * @var string Locales
     */
    protected static $_environment;

    /**
     * Default locale
     *
     * @var string Locales
     */
    protected static $_default = 'en';

    /**
     * Generates a locale object
     * If no locale is given a automatic search is done
     * Then the most probable locale will be automatically set
     * Search order is
     *  1. Given Locale
     *  2. HTTP Client
     *  3. Server Environment
     *  4. Framework Standard
     *
     * @param  string $locale (Optional) Locale for parsing input
     * @throws Zend_Locale_Exception When autodetection has been failed
     */
    public function __construct($locale = null)
    {
        if (empty(self::$_auto) === true) {
            self::$_auto        = $this->getDefault(null, false);
            self::$_browser     = $this->getDefault(self::BROWSER, false);
            self::$_environment = $this->getDefault(self::ENVIRONMENT, false);
            if ((empty($locale) === true) and (empty(self::$_auto) === true) and
                (empty(self::$_default) === true)) {
                require_once 'Zend/Locale/Exception.php';
                throw new Zend_Locale_Exception('Autodetection of Locale has been failed!');
            }
        }

        if ($locale instanceof Zend_Locale) {
            $locale = $locale->toString();
        }

        $this->setLocale($locale);
    }

    /**
     * Serialization Interface
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this);
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return (string) $this->_locale;
    }

    /**
     * Returns a string representation of the object
     * Alias for toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Search the locale automatically and return all used locales
     * ordered by quality
     *
     * Standard Searchorder is
     * - getBrowser
     * - getEnvironment
     * - getFramework
     *
     * @param  string  $searchorder (Optional) Searchorder
     * @param  boolean $fastsearch  (Optional) Returnes the first found locale array when true
     *                              otherwise all found default locales will be returned
     * @return array Returns an array of all locale string
     */
    public function getDefault($searchorder = null, $fastsearch = null)
    {
        $languages = array();
        if ($searchorder === self::ENVIRONMENT) {
            $languages = $this->getEnvironment();
            if ((empty($languages) === true) or ($fastsearch === false)) {
                $languages = $this->getFramework() + $languages;
                $languages = $this->getBrowser() + $languages;
            }
        } else if ($searchorder === self::FRAMEWORK) {
            $languages = $this->getFramework();
            if ((empty($languages) === true) or ($fastsearch === false)) {
                $languages = $this->getEnvironment() + $languages;
                $languages = $this->getBrowser() + $languages;
            }
        } else {
            $languages = $this->getBrowser();
            if ((empty($languages) === true) or ($fastsearch === false)) {
                $languages = $this->getEnvironment() + $languages;
                $languages = $this->getFramework() + $languages;
            }
        }

        if (isset($languages[self::$_default]) === false) {
            $languages[self::$_default] = 0.1;
        }

        return $languages;
    }

    /**
     * Sets a new default locale
     *
     * @param  string|Zend_Locale $locale Locale to set
     * @throws Zend_Locale_Exception When a autolocale was given
     * @throws Zend_Locale_Exception When a unknown locale was given
     * @return boolean
     */
    public static function setDefault($locale)
    {
        if (($locale === 'auto') or ($locale === 'root') or
            ($locale === 'environment') or ($locale === 'browser')) {
            require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception('Only full qualified locales can be used as default!');
        }

        if (isset(self::$_localeData[$locale]) === true) {
            self::$_default = $locale;
            return true;
        } else {
            $locale = explode('_', $locale);
            if (isset(self::$_localeData[$locale[0]]) === true) {
                self::$_default = $locale[0];
                return true;
            }
        }

        require_once 'Zend/Locale/Exception.php';
        throw new Zend_Locale_Exception("Unknown locale '$locale' can not be set as default!");
    }

    /**
     * Expects the Systems standard locale
     *
     * For Windows:
     * f.e.: LC_COLLATE=C;LC_CTYPE=German_Austria.1252;LC_MONETARY=C
     * would be recognised as de_AT
     *
     * @return array
     */
    public function getEnvironment()
    {
        if (self::$_environment !== null) {
            return self::$_environment;
        }

        $language  = setlocale(LC_ALL, 0);
        $languages = explode(';', $language);

        $languagearray = array();
        require_once 'Zend/Locale/Data/Translation.php';

        foreach ($languages as $locale) {
            if (strpos($locale, '=') !== false) {
                $language = substr($locale, strpos($locale, '='));
                $language = substr($language, 1);
            }

            if ($language !== 'C') {
                if (strpos($language, '.') !== false) {
                    $language = substr($language, 0, (strpos($language, '.') - 1));
                } else if (strpos($language, '@') !== false) {
                    $language = substr($language, 0, (strpos($language, '@') - 1));
                }

                $splitted = explode('_', $language);
                $language = (string) $language;
                if (isset(self::$_localeData[$language]) === true) {
                    $languagearray[$language] = 1;
                    if (strlen($language) > 4) {
                        $languagearray[substr($language, 0, 2)] = 1;
                    }

                    continue;
                }

                if (empty(Zend_Locale_Data_Translation::$localeTranslation[$splitted[0]]) === false) {
                    if (empty(Zend_Locale_Data_Translation::$localeTranslation[$splitted[1]]) === false) {
                        $languagearray[Zend_Locale_Data_Translation::$localeTranslation[$splitted[0]] . '_' .
                        Zend_Locale_Data_Translation::$localeTranslation[$splitted[1]]] = 1;
                    }

                    $languagearray[Zend_Locale_Data_Translation::$localeTranslation[$splitted[0]]] = 1;
                }
            }
        }

        self::$_environment = $languagearray;
        return $languagearray;
    }

    /**
     * Return an array of all accepted languages of the client
     * Expects RFC compilant Header !!
     *
     * The notation can be :
     * de,en-UK-US;q=0.5,fr-FR;q=0.2
     *
     * @return array - list of accepted languages including quality
     */
    public function getBrowser()
    {
        if (self::$_browser !== null) {
            return self::$_browser;
        }
        $httplanguages = getenv('HTTP_ACCEPT_LANGUAGE');

        $languages = array();
        if (empty($httplanguages) === true) {
            return $languages;
        }

        $accepted = preg_split('/,\s*/', $httplanguages);

        foreach ($accepted as $accept) {
            $result = preg_match('/^([a-z]{1,8}(?:[-_][a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',
                                 $accept, $match);

            if ($result < 1) {
                continue;
            }

            if (isset($match[2]) === true) {
                $quality = (float) $match[2];
            } else {
                $quality = 1.0;
            }

            $countrys = explode('-', $match[1]);
            $region   = array_shift($countrys);

            $country2 = explode('_', $region);
            $region   = array_shift($country2);

            foreach ($countrys as $country) {
                $languages[$region . '_' . strtoupper($country)] = $quality;
            }

            foreach ($country2 as $country) {
                $languages[$region . '_' . strtoupper($country)] = $quality;
            }

            if ((isset($languages[$region]) === false) || ($languages[$region] < $quality)) {
                $languages[$region] = $quality;
            }
        }

        self::$_browser = $languages;
        return $languages;
    }

    /**
     * Returns the locale which the framework is set to
     * 
     * @return array
     */
    public function getFramework()
    {
        $languages = array();
        return $languages;
    }

    /**
     * Sets a new locale
     *
     * @param  string|Zend_Locale $locale (Optional) New locale to set
     * @return void
     */
    public function setLocale($locale = null)
    {
        if (($locale === 'auto') or ($locale === null)) {
            $locale = self::$_auto;
        }

        if ($locale === 'browser') {
            $locale = self::$_browser;
        }

        if ($locale === 'environment') {
            $locale = self::$_environment;
        }

        if (is_array($locale) === true) {
            $locale = key($locale);
        }

        if (isset(self::$_localeData[(string) $locale]) === false) {
            $region = substr($locale, 0, 3);
            if (isset($region[2]) === true) {
                if (($region[2] === '_') or ($region[2] === '-')) {
                    $region = substr($region, 0, 2);
                }
            }

            if (isset(self::$_localeData[(string) $region]) === true) {
                $this->_locale = $region;
            } else {
                $this->_locale = 'root';
            }
        } else {
            $this->_locale = $locale;
        }
    }

    /**
     * Returns the language part of the locale
     *
     * @return language
     */
    public function getLanguage()
    {
        $locale = explode('_', $this->_locale);
        return $locale[0];
    }

    /**
     * Returns the region part of the locale if available
     *
     * @return string|false - Regionstring
     */
    public function getRegion()
    {
        $locale = explode('_', $this->_locale);
        if (isset($locale[1]) === true) {
            return $locale[1];
        }

        return false;
    }

    /**
     * Return the accepted charset of the client
     * 
     * @return string
     */
    public function getHttpCharset()
    {
        $httpcharsets = getenv('HTTP_ACCEPT_CHARSET');

        $charsets = array();
        if ($httpcharsets === false) {
            return $charsets;
        }

        $accepted = preg_split('/,\s*/', $httpcharsets);
        foreach ($accepted as $accept) {
            if (empty($accept) === true) {
                continue;
            }

            if (strpos($accept, ';') !== false) {
                $quality        = (float) substr($accept, (strpos($accept, '=') + 1));
                $pos            = substr($accept, 0, strpos($accept, ';'));
                $charsets[$pos] = $quality;
            } else {
                $quality           = 1.0;
                $charsets[$accept] = $quality;
            }
        }

        return $charsets;
    }

    /**
     * Returns true if both locales are equal
     *
     * @param  Zend_Locale $object Locale to check for equality
     * @return boolean
     */
    public function equals(Zend_Locale $object)
    {
        if ($object->toString() === $this->toString()) {
            return true;
        }

        return false;
    }

    /**
     * Returns localized informations as array, supported are several
     * types of informations.
     * For detailed information about the types look into the documentation
     *
     * @param  string             $path   (Optional) Type of information to return
     * @param  string|Zend_Locale $locale (Optional) Locale|Language for which this informations should be returned
     * @param  string             $value  (Optional) Value for detail list
     * @return array Array with the wished information in the given language
     */
    public function getTranslationList($path = null, $locale = null, $value = null)
    {
        if ($locale === null) {
            $locale = $this->_locale;
        }

        if ($locale === 'auto') {
            $locale = self::$_auto;
        }

        if ($locale === 'browser') {
            $locale = self::$_browser;
        }

        if ($locale === 'environment') {
            $locale = self::$_environment;
        }

        if (is_array($locale) === true) {
            $locale = key($locale);
        }

        require_once 'Zend/Locale/Data.php';
        $result = Zend_Locale_Data::getList($locale, $path, $value);
        if (empty($result) === true) {
            return false;
        }

        return $result;
    }

    /**
     * Returns an array with the name of all languages translated to the given language
     *
     * @param  string|Zend_Locale $locale (Optional) Locale for language translation
     * @return array
     */
    public function getLanguageTranslationList($locale = null)
    {
        return $this->getTranslationList('language', $locale);
    }

    /**
     * Returns an array with the name of all scripts translated to the given language
     *
     * @param  string|Zend_Locale $locale (Optional) Locale for script translation
     * @return array
     */
    public function getScriptTranslationList($locale = null)
    {
        return $this->getTranslationList('script', $locale);
    }

    /**
     * Returns an array with the name of all countries translated to the given language
     *
     * @param  string|Zend_Locale $locale (Optional) Locale for country translation
     * @return array
     */
    public function getCountryTranslationList($locale = null)
    {
        return $this->getTranslationList('territory', $locale, 2);
    }

    /**
     * Returns an array with the name of all territories translated to the given language
     * All territories contains other countries.
     *
     * @param  string|Zend_Locale $locale (Optional) Locale for territory translation
     * @return array
     */
    public function getTerritoryTranslationList($locale = null)
    {
        return $this->getTranslationList('territory', $locale, 1);
    }

    /**
     * Returns a localized information string, supported are several types of informations.
     * For detailed information about the types look into the documentation
     *
     * @param  string             $value  Name to get detailed information about
     * @param  string             $path   (Optional) Type of information to return
     * @param  string|Zend_Locale $locale (Optional) Locale|Language for which this informations should be returned
     * @return string|false The wished information in the given language
     */
    public function getTranslation($value = null, $path = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->_locale;
        }

        if ($locale === 'auto') {
            $locale = self::$_auto;
        }

        if ($locale === 'browser') {
            $locale = self::$_browser;
        }

        if ($locale === 'environment') {
            $locale = self::$_environment;
        }

        if (is_array($locale) === true) {
            $locale = key($locale);
        }

        require_once 'Zend/Locale/Data.php';
        $result = Zend_Locale_Data::getContent($locale, $path, $value);
        if (empty($result) === true) {
            return false;
        }

        return $result;
    }

    /**
     * Returns the localized language name
     *
     * @param  string $value  Name to get detailed information about
     * @param  string $locale (Optional) Locale for language translation
     * @return array
     */
    public function getLanguageTranslation($value, $locale = null)
    {
        return $this->getTranslation($value, 'language', $locale);
    }

    /**
     * Returns the localized script name
     *
     * @param  string $value  Name to get detailed information about
     * @param  string $locale (Optional) locale for script translation
     * @return array
     */
    public function getScriptTranslation($value, $locale = null)
    {
        return $this->getTranslation($value, 'script', $locale);
    }

    /**
     * Returns the localized country name
     *
     * @param  string             $value  Name to get detailed information about
     * @param  string|Zend_Locale $locale (Optional) Locale for country translation
     * @return array
     */
    public function getCountryTranslation($value, $locale = null)
    {
        return $this->getTranslation($value, 'country', $locale);
    }

    /**
     * Returns the localized territory name
     * All territories contains other countries.
     *
     * @param  string             $value  Name to get detailed information about
     * @param  string|Zend_Locale $locale (Optional) Locale for territory translation
     * @return array
     */
    public function getTerritoryTranslation($value, $locale = null)
    {
        return $this->getTranslation($value, 'territory', $locale);
    }

    /**
     * Returns an array with translated yes strings
     *
     * @param  string|Zend_Locale $locale (Optional) Locale for language translation (defaults to $this locale)
     * @return array
     */
    public function getQuestion($locale = null)
    {
        if ($locale === null) {
            $locale = $this->_locale;
        }

        if ($locale === 'auto') {
            $locale = self::$_auto;
        }

        if ($locale === 'browser') {
            $locale = self::$_browser;
        }

        if ($locale === 'environment') {
            $locale = self::$_environment;
        }

        if (is_array($locale) === true) {
            $locale = key($locale);
        }

        require_once 'Zend/Locale/Data.php';
        $quest             = Zend_Locale_Data::getList($locale, 'question');
        $yes               = explode(':', $quest['yes']);
        $no                = explode(':', $quest['no']);
        $quest['yes']      = $yes[0];
        $quest['yesarray'] = $yes;
        $quest['no']       = $no[0];
        $quest['noarray']  = $no;
        $quest['yesexpr']  = $this->_getRegex($yes);
        $quest['noexpr']   = $this->_getRegex($no);

        return $quest;
    }

    /**
     * Internal function for creating a regex
     *
     * @param  string $input Regex to parse
     * @return string
     */
    private function _getRegex($input)
    {
        $regex = '';
        if (is_array($input) === true) {
            $regex = '^';
            $start = true;
            foreach ($input as $row) {
                if ($start === false) {
                    $regex .= '|';
                }

                $start  = false;
                $regex .= '(';
                $one    = null;
                if (strlen($row) > 2) {
                    $one = true;
                }

                foreach (str_split($row, 1) as $char) {
                    $regex .= '[' . $char;
                    $regex .= strtoupper($char) . ']';
                    if ($one === true) {
                        $one    = false;
                        $regex .= '(';
                    }
                }

                if ($one === false) {
                    $regex .= ')';
                }

                $regex .= '?)';
            }
        }

        return $regex;
    }

    /**
     * Checks if a locale identifier is a real locale or not
     * Examples:
     * "en_XX" refers to "en", which returns true
     * "XX_yy" refers to "root", which returns false
     *
     * @param  string|Zend_Locale $locale Locale to check for
     * @param  boolean            $create (Optional) If true, create a default locale, if $locale is empty
     * @return false|string False if given locale is not a locale, else the locale identifier is returned
     */
    public static function isLocale($locale, $create = false)
    {
        if (empty($locale) and ($create === true)) {
            $locale = new self();
        }

        if ($locale instanceof Zend_Locale) {
            return $locale->toString();
        }

        if (is_string($locale) === false) {
            return false;
        }

        if (empty(self::$_auto) === true) {
            $temp               = new self($locale);
            self::$_auto        = $temp->getDefault(null, false);
            self::$_browser     = $temp->getDefault(self::BROWSER, false);
            self::$_environment = $temp->getDefault(self::ENVIRONMENT, false);
        }

        if ($locale === 'auto') {
            $locale = self::$_auto;
        }

        if ($locale === 'browser') {
            $locale = self::$_browser;
        }

        if ($locale === 'environment') {
            $locale = self::$_environment;
        }

        if (is_array($locale) === true) {
            $locale = key($locale);
        }

        if (isset(self::$_localeData[$locale]) === true) {
            return $locale;
        } else {
            $locale = explode('_', $locale);
            if (isset(self::$_localeData[$locale[0]]) === true) {
                return $locale[0];
            }
        }

        return false;
    }

    /**
     * Returns a list of all known locales where the locale is the key
     * Only real locales are returned, the internal locales 'root', 'auto', 'browser'
     * and 'environment' are suppressed
     * 
     * @return array List of all Locales
     */
    public static function getLocaleList()
    {
        $list = self::$_localeData;
        unset($list['root']);
        unset($list['auto']);
        unset($list['browser']);
        unset($list['environment']);
        return $list;
    }

    /**
     * Sets a cache
     *
     * @param  Zend_Cache_Core $cache Cache to set
     * @return void
     */
    public static function setCache(Zend_Cache_Core $cache)
    {
        require_once 'Zend/Locale/Data.php';
        Zend_Locale_Data::setCache($cache);
    }

    /**
     * Returns the set cache
     *
     * @return Zend_Cache_Core The set cache
     */
    public static function getCache()
    {
        require_once 'Zend/Locale/Data.php';
        $cache = Zend_Locale_Data::getCache();
        return $cache;
    }
}
