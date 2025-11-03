<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use Pikselin\Elemental\Video\ElementalVideo;
use Fractas\ElementalStylings\Forms\StylingOptionsetField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $TextAlign
 * @extends Extension<ElementalVideo&static>
 */
class StylingTextAlign extends Extension
{
    private static $db = [
        'TextAlign' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Text Align';

    /**
     * @var string
     */
    private static $plural_name = 'Text Aligns';

    /**
     * @config
     *
     * @var array
     */
    private static $textalign = [];

    public function getStylingTextAlignNice($key)
    {
        return (!empty($this->getOwner()->config()->get('textalign')[$key])) ? $this->getOwner()->config()->get('textalign')[$key] : $key;
    }

    public function getStylingTextAlignData()
    {
        return ArrayData::create([
               'Label' => self::config()->get('singular_name'),
               'Value' => $this->getStylingTextAlignNice($this->getOwner()->TextAlign),
           ]);
    }

    /**
     * @return string
     */
    public function getTextAlignVariant()
    {
        $textalign = $this->getOwner()->TextAlign;
        $textaligns = $this->getOwner()->config()->get('textalign');

        if (isset($textaligns[$textalign])) {
            $textalign = strtolower($textalign);
        } else {
            $textalign = '';
        }

        return 'textalign-'.$textalign;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('TextAlign');
        $textalign = $this->getOwner()->config()->get('textalign');
        if ($textalign && count($textalign) > 1) {
            $fields->addFieldToTab(
                'Root.Styling',
                StylingOptionsetField::create('TextAlign', _t(__CLASS__.'.TEXTALIGN', 'Text Align'), $textalign)
            );
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $textalign = $this->getOwner()->config()->get('textalign');
        $textalign = key($textalign);

        $this->getOwner()->TextAlign = $textalign;

        parent::populateDefaults();
    }
}
