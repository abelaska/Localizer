<?
include_once dirname(__FILE__) . "/localizer.php";

/** Czech localization of example application.
 */
class CzechLocalization extends DefaultCzechLocalization {

    function __construct() {
        parent::__construct(array("localized string, value=%s" => "lokalizovaný řetězec, hodnota=%s", "%d ship" => array("%d loď", "%d lodě", "%d lodí")));
    }

}

/** English localization of example application.
 */
class EnglishLocalization extends DefaultEnglishLocalization {

    function __construct() {
        parent::__construct(array("lokalizovaný řetězec, hodnota=%s" => "localized string, value=%s", "%d loď" => array("%d ship", "%d ships")));
    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Localizer example</title>
        <style type="text/css">
            td.key {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <?
        Localizer::add("en", new DefaultEnglishLocalization(), true);
        Localizer::add("cs", new CzechLocalization());
        ?>
        <h3>
            English default localization with automatic localization detection (detected: <? echo Localizer::detectedLocalizationLocale(true); ?>, used: <? echo Localizer::usedLocalizationLocale(true); ?>).
        </h3>
        <table>
            <tr>
                <td class="key">__("localized string, value=%s", "TEST")</td>
                <td><? echo __("localized string, value=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("not localized string, value=%s", "TEST")</td>
                <td><? echo __("not localized string, value=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("%d ship", 1)</td>
                <td><? echo __("%d ship", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n(1, "%d ship")</td>
                <td><? echo __n("%d ship", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n(1, "%d ship,"%d ships","%d ships")</td>
                <td><? echo __n("%d ship", "%d ships", "%d ships", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n(1, "%d ship", "%d ships")</td>
                <td><? echo __n("%d ship", "%d ships", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", "%d ships", 2)</td>
                <td><? echo __n("%d ship", "%d ships", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", "%d ships", 5)</td>
                <td><? echo __n("%d ship", "%d ships", 5); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 1)</td>
                <td><? echo __n("%d tractor", "%d tractors", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 2)</td>
                <td><? echo __n("%d tractor", "%d tractors", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 5)</td>
                <td><? echo __n("%d tractor", "%d tractors", 5); ?></td>
            </tr>
            <tr>
                <td class="key">JS configuration</td>
                <td><? echo Localizer::renderJS(); ?></td>
            </tr>
        </table>
        <br/>
        <?
        Localizer::select("cs");
        ?>
        <h3>
            English default localization with selected Czech localization (detected: <? echo Localizer::detectedLocalizationLocale(true); ?>, used: <? echo Localizer::usedLocalizationLocale(true); ?>).
        </h3>
        <table>
            <tr>
                <td class="key">__("localized string, value=%s", "TEST")</td>
                <td><? echo __("localized string, value=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("not localized string, value=%s", "TEST")</td>
                <td><? echo __("not localized string, value=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("%d ship", 1)</td>
                <td><? echo __("%d ship", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", 1)</td>
                <td><? echo __n("%d ship", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", "%d ships", 1)</td>
                <td><? echo __n("%d ship", "%d ships", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", "%d ships", 2)</td>
                <td><? echo __n("%d ship", "%d ships", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d ship", "%d ships", 5)</td>
                <td><? echo __n("%d ship", "%d ships", 5); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 1)</td>
                <td><? echo __n("%d tractor", "%d tractors", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 2)</td>
                <td><? echo __n("%d tractor", "%d tractors", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d tractor", "%d tractors", 5)</td>
                <td><? echo __n("%d tractor", "%d tractors", 5); ?></td>
            </tr>
            <tr>
                <td class="key">JS configuration</td>
                <td><? echo Localizer::renderJS(); ?></td>
            </tr>
        </table>
        <br/>
        <?
        Localizer::clear();

        Localizer::add("cs", new DefaultCzechLocalization(), true);
        Localizer::add("en", new EnglishLocalization());
        ?>
        <h3>
            Czech default localization with automatic localization detection (detected: <? echo Localizer::detectedLocalizationLocale(true); ?>, used: <? echo Localizer::usedLocalizationLocale(true); ?>).
        </h3>
        <table>
            <tr>
                <td class="key">__("lokalizovaný řetězec, hodnota=%s", "TEST")</td>
                <td><? echo __("lokalizovaný řetězec, hodnota=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("nelokalizovaný řetězec, hodnota=%s", "TEST")</td>
                <td><? echo __("nelokalizovaný řetězec, hodnota=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("%d loď", 1)</td>
                <td><? echo __("%d loď", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", 1)</td>
                <td><? echo __n("%d loď", "%d lodě", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 1)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 2)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 5)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 5); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 1)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 2)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 5)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 5); ?></td>
            </tr>
            <tr>
                <td class="key">JS configuration</td>
                <td><? echo Localizer::renderJS(); ?></td>
            </tr>
        </table>
        <br/>
        <?
        Localizer::select("en");
        ?>
        <h3>
            Czech default localization with selected English localization (detected: <? echo Localizer::detectedLocalizationLocale(true); ?>, used: <? echo Localizer::usedLocalizationLocale(true); ?>).
        </h3>
        <table>
            <tr>
                <td class="key">__("lokalizovaný řetězec, hodnota=%s", "TEST")</td>
                <td><? echo __("lokalizovaný řetězec, hodnota=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("nelokalizovaný řetězec, hodnota=%s", "TEST")</td>
                <td><? echo __("nelokalizovaný řetězec, hodnota=%s", "TEST"); ?></td>
            </tr>
            <tr>
                <td class="key">__("%d loď", 1)</td>
                <td><? echo __("%d loď", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", 1)</td>
                <td><? echo __n("%d loď", "%d lodě", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 1)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 2)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d loď", "%d lodě", "%d lodí", 5)</td>
                <td><? echo __n("%d loď", "%d lodě", "%d lodí", 5); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 1)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 1); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 2)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 2); ?></td>
            </tr>
            <tr>
                <td class="key">__n("%d traktor", "%d traktory", "%d traktorů", 5)</td>
                <td><? echo __n("%d traktor", "%d traktory", "%d traktorů", 5); ?></td>
            </tr>
            <tr>
                <td class="key">JS configuration</td>
                <td><? echo Localizer::renderJS(); ?></td>
            </tr>
        </table>
        <script type="text/javascript" src="localizer.js"></script>
        <script type="text/javascript">
            <? echo Localizer::renderJS(); ?>
            document.write(__("loď") + "<br/>");
        </script>
    </body>
</html>
