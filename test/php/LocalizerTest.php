<?php

require_once dirname(__FILE__) . '/../../localizer.php';

/** Czech localization of example application.
 */
class CzechLocalization extends DefaultCzechLocalization {

    function __construct() {
        parent::__construct(array(
            "car" => "auto",
            "%s car" => "%s auto",
            "%d car" => array("%d auto", "%d auta", "%d aut")));
    }

}

/** Empty Czech localization of example application.
 */
class CzechEmptyLocalization extends DefaultCzechLocalization {

    function __construct() {
        parent::__construct(array());
    }

}

/** English localization of example application.
 */
class EnglishLocalization extends DefaultEnglishLocalization {

    function __construct() {
        parent::__construct(array(
            '%d auto' => array('%d car', '%d cars')));
    }

}

/**
 * Test class for Localizer.
 */
class LocalizerTest extends PHPUnit_Framework_TestCase {

    public function testUninitializedStringLocaleWithoutDefaultLocale() {
        Localizer::clear();
        try {
            Localizer::locale('car');
        } catch (Exception $e) {
            $this->assertEquals('Default locale not set', $e->getMessage());
        }
    }

    public function testUninitializedStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        $this->assertEquals('car', Localizer::locale('car'));
    }

    public function testUninitializedEmptyStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        $this->assertEquals('', Localizer::locale(''));
    }

    public function testUninitializedParametrizedStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        $this->assertEquals('text 1', __("text %s", 1));
    }

    public function testUninitializedArrayLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        $this->assertEquals('text0', __n("text0", "text1", 1));
        $this->assertEquals('text1', __n("text0", "text1", 2));
    }

    public function testUninitializedParametrizedArrayLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        $this->assertEquals('text0 1', __n("text0 %s", "text1 %s", 1));
        $this->assertEquals('text1 2', __n("text0 %s", "text1 %s", 2));
    }

    public function testInitializedStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechLocalization());
        Localizer::select('cs');
        $this->assertEquals('auto', __('car'));
        $this->assertEquals('plane', __('plane'));
    }

    public function testEmptyInitializedStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechEmptyLocalization());
        Localizer::select('cs');
        $this->assertEquals('plane', __('plane'));
        $this->assertEquals('text0', __n("text0", "text1", 1));
        $this->assertEquals('text1', __n("text0", "text1", 2));
    }

    public function testInitializedParametrizedStringLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechLocalization());
        Localizer::select('cs');
        $this->assertEquals("ford auto", __('%s car', 'ford'));
        $this->assertEquals("fast plane", __('%s plane', 'fast'));
    }

    public function testInitializedParametrizedArrayLocale() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechLocalization());
        Localizer::select('cs');
        $this->assertEquals("1 auto", __n('%d car', '%d cars', 1));
        $this->assertEquals("2 auta", __n('%d car', '%d cars', 2));
        $this->assertEquals("5 aut", __n('%d car', '%d cars', 5));
        $this->assertEquals("1 plane", __n('%d plane', '%d planes', 1));
        $this->assertEquals("2 planes", __n('%d plane', '%d planes', 2));
        $this->assertEquals("5 planes", __n('%d plane', '%d planes', 5));
    }

    public function testInitializedParametrizedArrayLocaleMissingPluralParameter() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechLocalization());
        Localizer::select('cs');
        $this->assertEquals("__n(!!!missing plural argument!!!,%d car,1)", __n('%d car', 1));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d car,2)", __n('%d car', 2));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d car,5)", __n('%d car', 5));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d plane,1)", __n('%d plane', 1));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d plane,2)", __n('%d plane', 2));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d plane,5)", __n('%d plane', 5));
    }

    public function testInitializedParametrizedArrayLocaleExtraPluralParameter() {
        Localizer::clear();
        Localizer::add('en', new DefaultEnglishLocalization(), true);
        Localizer::add('cs', new CzechLocalization());
        Localizer::select('cs');
        $this->assertEquals("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,1)", __n('%d car', '%d cars', '%d cars', 1));
        $this->assertEquals("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,2)", __n('%d car', '%d cars', '%d cars', 2));
        $this->assertEquals("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,5)", __n('%d car', '%d cars', '%d cars', 5));
        $this->assertEquals("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,1)", __n('%d plane', '%d planes', '%d planes', 1));
        $this->assertEquals("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,2)", __n('%d plane', '%d planes', '%d planes', 2));
        $this->assertEquals("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,5)", __n('%d plane', '%d planes', '%d planes', 5));
    }

    public function testInitializedParametrizedArrayLocaleDefaultMoreThan2Plurals() {
        Localizer::clear();
        Localizer::add('cs', new DefaultCzechLocalization(), true);
        Localizer::add('en', new EnglishLocalization());
        Localizer::select('en');
        $this->assertEquals("1 car", __n('%d auto', '%d auta', '%d aut', 1));
        $this->assertEquals("2 cars", __n('%d auto', '%d auta', '%d aut', 2));
        $this->assertEquals("5 cars", __n('%d auto', '%d auta', '%d aut', 5));
        $this->assertEquals("1 letadlo", __n('%d letadlo', '%d letadla', '%d letadel', 1));
        $this->assertEquals("2 letadla", __n('%d letadlo', '%d letadla', '%d letadel', 2));
        $this->assertEquals("5 letadel", __n('%d letadlo', '%d letadla', '%d letadel', 5));
    }

    public function testInitializedParametrizedArrayLocaleMissingPluralParameterDefaultMoreThan2Plurals() {
        Localizer::clear();
        Localizer::add('cs', new DefaultCzechLocalization(), true);
        Localizer::add('en', new EnglishLocalization());
        Localizer::select('en');
        $this->assertEquals("__n(!!!missing plural argument!!!,%d auto,%d auta,1)", __n('%d auto', '%d auta', 1));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d auto,%d auta,2)", __n('%d auto', '%d auta', 2));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d auto,%d auta,5)", __n('%d auto', '%d auta', 5));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,1)", __n('%d letadlo', '%d letadla', 1));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,2)", __n('%d letadlo', '%d letadla', 2));
        $this->assertEquals("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,5)", __n('%d letadlo', '%d letadla', 5));
    }

    public function testInitializedParametrizedArrayLocaleExtraPluralParameterDefaultMoreThan2Plurals() {
        Localizer::clear();
        Localizer::add('cs', new DefaultCzechLocalization(), true);
        Localizer::add('en', new EnglishLocalization());
        Localizer::select('en');
        $this->assertEquals("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,1)", __n('%d auto', '%d auta', '%d aut', '%d aut', 1));
        $this->assertEquals("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,2)", __n('%d auto', '%d auta', '%d aut', '%d aut', 2));
        $this->assertEquals("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,5)", __n('%d auto', '%d auta', '%d aut', '%d aut', 5));
        $this->assertEquals("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,1)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 1));
        $this->assertEquals("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,2)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 2));
        $this->assertEquals("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,5)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 5));
    }

}

?>
