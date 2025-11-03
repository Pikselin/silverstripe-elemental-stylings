<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $Size
 * @extends Extension<static>
 */
class StylingSize extends Extension
{
    private static $db = [
        'Size' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Size';

    /**
     * @var string
     */
    private static $plural_name = 'Sizes';

    /**
     * @config
     *
     * @var array
     */
    private static $size = [];

    public function getStylingSizeNice($key)
    {
        return (!empty($this->getOwner()->config()->get('size')[$key])) ? $this->getOwner()->config()->get('size')[$key] : $key;
    }

    public function getStylingSizeData()
    {
        return ArrayData::create([
           'Label' => self::config()->get('singular_name'),
           'Value' => $this->getStylingSizeNice($this->getOwner()->Size),
       ]);
    }

    /**
     * @return string
     */
    public function getSizeVariant()
    {
        $size = $this->getOwner()->Size;
        $sizes = $this->getOwner()->config()->get('size');

        if (isset($sizes[$size])) {
            $size = strtolower($size);
        } else {
            $size = '';
        }

        return 'size-'.$size;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $size = $this->getOwner()->config()->get('size');
        if ($size && count($size) > 1) {
            $fields->addFieldToTab('Root.Styling', DropdownField::create('Size', _t(__CLASS__.'.SIZE', 'Size'), $size));
        } else {
            $fields->removeByName('Size');
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $size = $this->getOwner()->config()->get('size');
        $size = reset($size);

        $this->getOwner()->Size = $size;
    }
}
