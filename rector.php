<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;

// --------------------------------------------------
// 👇 Inline Silverstripe stubs so Rector can parse without full vendor/
// --------------------------------------------------
call_user_func(static function (): void {
    $aliases = [
        // Common legacy -> new class aliases
        'SilverStripe\\ORM\\DataExtension' => 'SilverStripe\\Core\\Extension',

        // Common base classes (create dummy stand-ins)
        'SilverStripe\\Control\\Controller' => 'stdClass',
        'SilverStripe\\Control\\HTTPRequest' => 'stdClass',
        'SilverStripe\\CMS\\Controllers\\ContentController' => 'stdClass',
        'SilverStripe\\Admin\\LeftAndMain' => 'stdClass',
        'SilverStripe\\Forms\\FieldList' => 'stdClass',
        'SilverStripe\\Forms\\Form' => 'stdClass',
        'SilverStripe\\ORM\\DataObject' => 'stdClass',
        'SilverStripe\\Security\\Member' => 'stdClass',
        'SilverStripe\\View\\Requirements' => 'stdClass',
        'SilverStripe\\Assets\\File' => 'stdClass',
    ];

    foreach ($aliases as $old => $new) {
        if (!class_exists($old) && class_exists($new)) {
            class_alias($new, $old);
            continue;
        }
        if (!class_exists($old)) {
            // dynamically define dummy class so Rector can parse
            $parts = explode('\\', $old);
            $class = array_pop($parts);
            $ns = implode('\\', $parts);
            eval("namespace $ns; class $class {}");
        }
    }
});
// --------------------------------------------------

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    /**
     * Ensures it uses the "use" syntax. Example below:
     *
     * -use SilverStripe\ORM\DataExtension;
     * +use SilverStripe\Core\Extension;
     *
     * -class AttributesTaxonomyExtension extends DataExtension
     * +class AttributesTaxonomyExtension extends Extension
     */
    ->withImportNames(importShortClasses: true)
    // reverts to composer.json's php version if unset
    // ->withPhpSets(php84)
    // Controls how strongly Rector tries to enforce typed properties, parameters, and return types.
    ->withTypeCoverageLevel(1)
    // Controls how aggressively Rector removes unused code.
    ->withDeadCodeLevel(0)
    // Controls code cleanup and readability improvements — reformatting, simplifying expressions, etc.
    ->withCodeQualityLevel(0)
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_61,
        SilverstripeSetList::CODE_QUALITY,
        SilverstripeSetList::GORRIECOE_LINK_TO_SILVERSTRIPE_LINKFIELD,
        SilverstripeSetList::PROTECT_EXTENSION_HOOKS,
        SetList::CODING_STYLE,
    ])
    ->withSkip([
        PropertyFetchToMethodCallRector::class,
    ]);

