<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use DNADesign\Elemental\Models\ElementContent;
use DNADesign\ElementalUserForms\Model\ElementForm;
use Fractas\ElementalStylings\Forms\StylingOptionsetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $Width
 * @extends Extension<(ElementContent&static|ElementForm&static)>
 */
class StylingWidth extends Extension
{
    private static $db = [
        'Width' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Width';

    /**
     * @var string
     */
    private static $plural_name = 'Widths';

    /**
     * @config
     *
     * @var array
     */
    private static $width = [];

    public function getStylingWidthNice($key)
    {
        return (!empty($this->getOwner()->config()->get('width')[$key])) ? $this->getOwner()->config()->get('width')[$key] : $key;
    }

    public function getStylingWidthData()
    {
        return ArrayData::create([
           'Label' => self::config()->get('singular_name'),
           'Value' => $this->getStylingWidthNice($this->getOwner()->Width),
       ]);
    }

    /**
     * @return string
     */
    public function getWidthVariant()
    {
        $width = $this->getOwner()->Width;
        $widths = $this->getOwner()->config()->get('width');

        if (isset($widths[$width])) {
            $width = strtolower($width);
        } else {
            $width = '';
        }

        return $width;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $width = $this->getOwner()->config()->get('width');
        if ($width && count($width) > 1) {
            $fields->addFieldToTab('Root.Styling', StylingOptionsetField::create('Width', _t(__CLASS__.'.WIDTH', 'Width Size'), $width));
        } else {
            $fields->removeByName('Width');
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $width = $this->getOwner()->config()->get('width');
        $width = reset($width);

        $this->getOwner()->Width = $width;
    }
}
