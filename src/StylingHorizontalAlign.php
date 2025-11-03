<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use Fractas\ElementalStylings\Forms\StylingOptionsetField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $HorAlign
 * @extends Extension<static>
 */
class StylingHorizontalAlign extends Extension
{
    private static $db = [
        'HorAlign' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Horizontal Align';

    /**
     * @var string
     */
    private static $plural_name = 'Horizontal Aligns';

    /**
     * @config
     *
     * @var array
     */
    private static $horalign = [];

    public function getStylingHorizontalAlignNice($key)
    {
        return (!empty($this->getOwner()->config()->get('horalign')[$key])) ? $this->getOwner()->config()->get('horalign')[$key] : $key;
    }

    public function getStylingHorizontalAlignData()
    {
        return ArrayData::create([
               'Label' => self::config()->get('singular_name'),
               'Value' => $this->getStylingHorizontalAlignNice($this->getOwner()->HorAlign),
           ]);
    }

    /**
     * @return string
     */
    public function getHorAlignVariant()
    {
        $horalign = $this->getOwner()->HorAlign;
        $horaligns = $this->getOwner()->config()->get('horalign');

        if (isset($horaligns[$horalign])) {
            $horalign = strtolower($horalign);
        } else {
            $horalign = '';
        }

        return 'horalign-'.$horalign;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('HorAlign');
        $horalign = $this->getOwner()->config()->get('horalign');
        if ($horalign && count($horalign) > 1) {
            $fields->addFieldToTab(
                'Root.Styling',
                StylingOptionsetField::create('HorAlign', _t(__CLASS__.'.HORIZONTALALIGN', 'Horizontal Align'), $horalign)
            );
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $horalign = $this->getOwner()->config()->get('horalign');
        $horalign = key($horalign);

        $this->getOwner()->HorAlign = $horalign;
    }
}
