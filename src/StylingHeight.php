<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use Fractas\ElementalStylings\Forms\StylingOptionsetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $Height
 * @extends Extension<static>
 */
class StylingHeight extends Extension
{
    private static $db = [
        'Height' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Height';

    /**
     * @var string
     */
    private static $plural_name = 'Heights';

    /**
     * @config
     *
     * @var array
     */
    private static $height = [];

    public function getStylingHeightNice($key)
    {
        return (!empty($this->getOwner()->config()->get('height')[$key])) ? $this->getOwner()->config()->get('height')[$key] : $key;
    }

    public function getStylingHeightData()
    {
        return ArrayData::create([
           'Label' => self::config()->get('singular_name'),
           'Value' => $this->getStylingHeightNice($this->getOwner()->Height),
       ]);
    }

    /**
     * @return string
     */
    public function getHeightVariant()
    {
        $height = $this->getOwner()->Height;
        $heights = $this->getOwner()->config()->get('height');

        if (isset($heights[$height])) {
            $height = strtolower($height);
        } else {
            $height = '';
        }

        return 'height-'.$height;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $height = $this->getOwner()->config()->get('height');
        if ($height && count($height) > 1) {
            $fields->addFieldToTab('Root.Styling', StylingOptionsetField::create('Height', _t(__CLASS__.'.HEIGHT', 'Height Size'), $height));
        } else {
            $fields->removeByName('Height');
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $height = $this->getOwner()->config()->get('height');
        $height = reset($height);

        $this->getOwner()->Height = $height;
    }
}
