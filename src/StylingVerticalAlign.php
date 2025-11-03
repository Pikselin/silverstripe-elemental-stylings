<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use Fractas\ElementalStylings\Forms\StylingOptionsetField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $VerAlign
 * @extends Extension<static>
 */
class StylingVerticalAlign extends Extension
{
    private static $db = [
        'VerAlign' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Vertical Align';

    /**
     * @var string
     */
    private static $plural_name = 'Vertical Aligns';

    /**
     * @config
     *
     * @var array
     */
    private static $veralign = [];

    public function getStylingVerticalAlignNice($key)
    {
        return (!empty($this->getOwner()->config()->get('veralign')[$key])) ? $this->getOwner()->config()->get('veralign')[$key] : $key;
    }

    public function getStylingVerticalAlignData()
    {
        return ArrayData::create([
           'Label' => self::config()->get('singular_name'),
           'Value' => $this->getStylingVerticalAlignNice($this->getOwner()->VerAlign),
       ]);
    }

    /**
     * @return string
     */
    public function getVerAlignVariant()
    {
        $veralign = $this->getOwner()->VerAlign;
        $veraligns = $this->getOwner()->config()->get('veralign');

        if (isset($veraligns[$veralign])) {
            $veralign = strtolower($veralign);
        } else {
            $veralign = '';
        }

        return 'veralign-'.$veralign;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('VerAlign');
        $veralign = $this->getOwner()->config()->get('veralign');
        if ($veralign && count($veralign) > 1) {
            $fields->addFieldToTab(
                'Root.Styling',
                StylingOptionsetField::create('VerAlign', _t(__CLASS__.'.VERTICALALIGN', 'Vertical Align'), $veralign)
            );
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        if ($this->getOwner()->config()->get('stop_veralign_inheritance')) {
            $veralign = $this->getOwner()->config()->get('veralign', Config::UNINHERITED);
        } else {
            $veralign = $this->getOwner()->config()->get('veralign');
        }

        $veralign = key($veralign);
        $this->getOwner()->VerAlign = $veralign;

        parent::populateDefaults();
    }
}
