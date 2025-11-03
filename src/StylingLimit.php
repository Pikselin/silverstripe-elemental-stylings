<?php

namespace Fractas\ElementalStylings;

use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;

/**
 * @property ?string $Limit
 * @extends Extension<static>
 */
class StylingLimit extends Extension
{
    private static $db = [
        'Limit' => 'Varchar(255)',
    ];

    /**
     * @var string
     */
    private static $singular_name = 'Limit';

    /**
     * @var string
     */
    private static $plural_name = 'Limits';

    /**
     * @config
     *
     * @var array
     */
    private static $limit = [];

    public function getStylingLimitNice($key)
    {
        return (!empty($this->getOwner()->config()->get('limit')[$key])) ? $this->getOwner()->config()->get('limit')[$key] : $key;
    }

    public function getStylingLimitData()
    {
        return ArrayData::create([
           'Label' => self::config()->get('singular_name'),
           'Value' => $this->getStylingLimitNice($this->getOwner()->Limit),
       ]);
    }

    /**
     * @return string
     */
    public function getLimitVariant()
    {
        $limit = $this->getOwner()->Limit;
        $limits = $this->getOwner()->config()->get('limit');

        if (isset($limits[$limit])) {
            $limit = strtolower($limit);
        } else {
            $limit = '';
        }

        return 'limit-'.$limit;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        $limit = $this->getOwner()->config()->get('limit');
        if ($limit && count($limit) > 1) {
            $fields->addFieldToTab('Root.Styling', DropdownField::create('Limit', _t(__CLASS__.'.LIMIT', 'Limit'), $limit));
        } else {
            $fields->removeByName('Limit');
        }

        return $fields;
    }

    public function onAfterPopulateDefaults()
    {
        $limit = $this->getOwner()->config()->get('limit');
        $limit = reset($limit);

        $this->getOwner()->Limit = $limit;

        parent::populateDefaults();
    }
}
