<?php

/** Localizer - simple PHP & JavaScript localization utility similar to gettext.
 * @link http://abelaska.github.com/localizer/
 * @author Alois Bělaška, http://lojzuv.wordpress.com/
 * @copyright 2011 Alois Bělaška
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */

/** Localization interface definition.
 */
interface Localization {

    /** List of localized strings.
     * @return array(key=>string, value=>[string||array of strings])
     */
    function strings();

    /** Localization of string.
     * @param string
     * @return string
     */
    function locale($string);

    /** String index of plural.
     * @param integer
     * @return integer
     */
    function nplural($n);

    /** Definition of JS function providing nplural functionality.
     * @return string
     */
    function npluralJS();

    /** Count of plural forms specific for language.
     * @return integer
     */
    function nplurals();
}

/** Abstract implementation of localization.
 */
abstract class Localization_Abstract implements Localization {

    private $strings;

    /**
     * @param array(key=>string, value=>[string||array of strings]) or null
     */
    function __construct() {
        $args = func_get_args();
        $this->strings = count($args) > 0 ? $args[0] : null;
    }

    /** List of all localized strings.
     * @return array(key=>string, value=>[string||array of strings]) or null
     */
    function strings() {
        return $this->strings;
    }

    /** Localization of string.
     * @param string
     * @return string or array of strings
     */
    function locale($string) {
        return isset($this->strings) ? (isset($this->strings[$string]) ? $this->strings[$string] : $string) : $string;
    }

}

/** Class providing static localization functions.
 */
class Localizer {

    private static $localizations = array();
    private static $selectedLocale;
    private static $defaultLocale;
    private static $detectedLocale;
    private static $usedLocale;

    /** Get default localization.
     * @return Localization
     */
    static function defaultLocalization() {
        if (!Localizer::$defaultLocale) {
            throw new Exception("Default locale not set");
        }
        return Localizer::$localizations[Localizer::$defaultLocale];
    }

    /** Get autodetected locale.
     * @param boolean
     * @return string||null
     */
    public static function detectedLocalizationLocale($detect = false) {
        if ($detect) {
            Localizer::detectLocalization();
        }
        return Localizer::$detectedLocale;
    }

    /** Get used locale.
     * @param boolean
     * @return string||null
     */
    public static function usedLocalizationLocale($detect = false) {
        if ($detect) {
            Localizer::localization();
        }
        return Localizer::$usedLocale;
    }

    /** Add supported localization.
     * @param string
     * @param Localization
     * @param boolean
     */
    static function add($locale, Localization $localization, $isDefault = false) {
        Localizer::$localizations[$locale] = $localization;
        if ($isDefault) {
            Localizer::$defaultLocale = $locale;
        }
    }

    /** Empty list of supported localizations.
     */
    static function clear() {
        Localizer::$selectedLocale = null;
        Localizer::$defaultLocale = null;
        Localizer::$localizations = array();
    }

    /** Select localization to work with.
     * @param string
     */
    static function select($locale) {
        Localizer::$selectedLocale = $locale;
    }

    /** Set localization to default value @see defaultLocalization.
     */
    static function unselect() {
        Localizer::$selectedLocale = null;
    }

    /** Extract accepted languages from request header HTTP_ACCEPT_LANGUAGE.
     * @return array(key=>string,value=>integer)
     */
    private static function acceptedLanguages() {
        $langs = array();
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            if (count($lang_parse[1])) {
                $langs = array_combine(str_replace(".", "", $lang_parse[1]), $lang_parse[4]);
                foreach ($langs as $lang => $val) {
                    if ($val === '') {
                        $langs[$lang] = 1;
                    }
                }
                arsort($langs, SORT_NUMERIC);
            }
        }
        return $langs;
    }

    /** Localization autodetection.
     * @param Localization
     * @return Localization||null
     */
    public static function detectLocalization($defaultLocalization = null) {
        Localizer::$detectedLocale = null;
        $acceptedLanguages = Localizer::acceptedLanguages();
        $localization = $defaultLocalization;
        foreach ($acceptedLanguages as $loc => $priority) {
            if (isset(Localizer::$localizations[$loc])) {
                $localization = Localizer::$localizations[$loc];
                if ($localization) {
                    Localizer::$detectedLocale = $loc;
                }
                break;
            }

            // kontrola slozenych identifikatoru lokalizace, ex. cs_CZ
            $parts = explode("-", $loc, 2);
            if (count($parts) < 2) {
                $parts = explode("_", $loc, 2);
            }
            $loc = $parts[0];

            if (isset(Localizer::$localizations[$loc])) {
                $localization = Localizer::$localizations[$loc];
                if ($localization) {
                    Localizer::$detectedLocale = $loc;
                }
                break;
            }
        }
        return $localization;
    }

    /** Localization selection (manually selected, by HTTP header or default).
     * @return Localization
     */
    private static function localization() {
        Localizer::$usedLocale = null;
        $localization = null;
        if (Localizer::$selectedLocale) {
            if (isset(Localizer::$localizations[Localizer::$selectedLocale])) {
                $localization = Localizer::$localizations[Localizer::$selectedLocale];
                Localizer::$usedLocale = Localizer::$selectedLocale;
            } else {
                $parts = explode("-", Localizer::$selectedLocale, 2);
                if (count($parts) < 2) {
                    $parts = explode("_", Localizer::$selectedLocale, 2);
                }
                if (isset(Localizer::$localizations[$parts[0]])) {
                    $localization = Localizer::$localizations[$parts[0]];
                    Localizer::$usedLocale = $parts[0];
                } else {
                    throw new Exception("Localization '" . Localizer::$selectedLocale . "' not found");
                }
            }
        } else {
            $localization = Localizer::detectLocalization(Localizer::defaultLocalization());
            if ($localization) {
                Localizer::$usedLocale = Localizer::detectedLocalizationLocale();
            }
        }
        return $localization;
    }

    /** Localization of string.
     * @param string
     * @return string
     */
    static function locale($string) {
        return Localizer::localization()->locale($string);
    }

    /** Numeric string localization.
     * @param integer
     * @param string0
     * @param string1
     * @param [, string2...stringN]
     */
    static function nlocale() {
        $args = func_get_args();
        if (is_array($args) && count($args) == 1) {
            $args = $args[0];
        }
        $number = $args[count($args) - 1];
        unset($args[count($args) - 1]);
        $localization = Localizer::localization();
        $locale = $localization->locale($args[0]);
        if (is_array($locale)) {
            $locale = $locale[$localization->nplural($number)];
        } else {
            $locale = $args[Localizer::defaultLocalization()->nplural($number)];
        }
        return $locale;
    }

    /** Render configuration of JavaScript localization implementation.
     * @return string
     */
    static function renderJS() {
        $localization = Localizer::localization();

        $msgid_count = 0;

        $locale_var = "String.locale(" . Localizer::defaultLocalization()->nplurals() . "," . Localizer::defaultLocalization()->npluralJS() . "," . $localization->nplurals() . "," . $localization->npluralJS() . ",{";

        if (count($localization->strings()) > 0) {
            foreach ($localization->strings() as $key => $value) {
                if (!empty($key)) {
                    if ($msgid_count++ > 0) {
                        $locale_var .= ",";
                    }
                    if (is_array($value)) {
                        $locale_var .= "'$key':['" . implode("','", $value) . "']";
                    } else {
                        $locale_var .= "'$key':'" . $value . "'";
                    }
                }
            }
        }

        $locale_var .= "});";

        return $locale_var;
    }

}

/** String localization including vsprintf processing of localized string and additional parameters.
 * @param string
 * @param [, $arg1...$argN]
 */
function __() {
    $args = func_get_args();
    $stringOriginal = array_shift($args);
    $string = Localizer::locale($stringOriginal);
    if (!is_string($string)) {
        if (is_array($string)) {
            $string = $string[0];
        } else {
            $string = "__(!!!required string value!!!$stringOriginal)";
        }
    }
    if (count($args) > 0) {
        $string = vsprintf($string, $args);
    }
    return $string;
}

/** Numeric string localization.
 * @param integer
 * @param string0
 * @param string1
 * @param [, string2...stringN]
 */
function __n() {
    $args = func_get_args();
    $countArgs = count($args);
    $requiredArgs = Localizer::defaultLocalization()->nplurals() + 1;
    if ($countArgs == $requiredArgs) {
        $string = Localizer::nlocale($args);
        return vsprintf($string, $args[count($args) - 1]);
    } elseif ($countArgs < $requiredArgs) {
        $err = "__n(";
        while ($countArgs++ < $requiredArgs) {
            $err .= "!!!missing plural argument!!!";
            if ($countArgs > 0) {
                $err .= ",";
            }
        }
        $err .= implode(",", $args) . ")";
        return $err;
    } else {
        return "__n(!!!too many plurals!!!," . implode(",", $args) . ")";
    }
}

/** Default Czech localization implementation.
 */
class DefaultCzechLocalization extends Localization_Abstract {

    function nplural($n) {
        return ($n == 1) ? 0 : (($n >= 2 && $n <= 4) ? 1 : 2);
    }

    function npluralJS() {
        return "function(n){return (n==1)?0:((n>=2&&n<=4)?1:2)}";
    }

    function nplurals() {
        return 3;
    }

}

/** Default English localization implementation.
 */
class DefaultEnglishLocalization extends Localization_Abstract {

    function nplural($n) {
        return $n < 2 ? 0 : 1;
    }

    function npluralJS() {
        return "function(n){return n<2?0:1;}";
    }

    function nplurals() {
        return 2;
    }

}
