<?php

namespace Fractas\ElementalStylings\Forms;

use SilverStripe\Model\List\ArrayList;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\View\Requirements;

class StylingOptionsetField extends OptionsetField
{
    public function Field($properties = [])
    {
        $options = [];
        $odd = false;

        foreach ($this->getSourceEmpty() as $value => $title) {
            $odd = !$odd;
            $options[] = $this->getFieldOption($value, $title, $odd);
        }

        $properties = array_merge($properties, [
            'Options' => ArrayList::create($options),
        ]);

        Requirements::javascript('pikselin/elemental-stylings:client/dist/js/StylingOptionsetField.js');

        return FormField::Field($properties);
    }
}
