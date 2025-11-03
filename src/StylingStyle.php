<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use DNADesign\Elemental\Models\BaseElement;
use Pikselin\Elemental\Video\ElementalVideo;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * @extends Extension<(BaseElement&static|ElementalVideo&static)>
 */
class StylingStyle extends Extension
{
    /**
     * @var string
     */
    private static $singular_name = 'Style';

    /**
     * @var string
     */
    private static $plural_name = 'Styles';

    /**
     * @config
     *
     * @var array
     */
    private static $style = [];

    public function getStylingStyleNice($key)
    {
        return (!empty($this->getOwner()->config()->get('styles')[$key])) ? $this->getOwner()->config()->get('styles')[$key] : $key;
    }

    public function getStylingStyleData()
    {
        return ArrayData::create([
               'Label' => self::config()->get('singular_name'),
               'Value' => $this->getStylingStyleNice($this->getOwner()->Style),
           ]);
    }

    public function getStylingTitleData()
    {
        return ArrayData::create([
               'Label' => 'Title',
               'Value' => $this->getOwner()->obj('ShowTitle')->Nice(),
           ]);
    }

    /**
     * @return string
     */
    protected function updateStyleVariant(&$style)
    {
        if (isset($style)) {
            $style = strtolower($style);
        } else {
            $style = '';
        }

        $style = 'style-'.$style;

        return $style;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $style = $this->getOwner()->config()->get('styles');
        if ($style && count($style) > 1) {
            $fields->addFieldToTab('Root.Styling', DropdownField::create('Style', _t(__CLASS__.'.STYLE', 'Style'), $style));
        } else {
            $fields->removeByName('Style');
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $style = $this->getOwner()->config()->get('styles');
        $style = array_key_first($style);

        $this->getOwner()->Style = $style;

        parent::populateDefaults();
    }
}
