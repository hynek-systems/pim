<?php

namespace Hynek\Pim\EntityField\Entity;

use App\Contracts\Form\FormElement;
use App\EntityField\Entity\EntityForm;
use Hynek\Pim\Forms\Controls\PriceInput;

class PriceEntityForm extends EntityForm
{
    public function getElement(): ?FormElement
    {
        return PriceInput::make(
            $this->getName(),
            $this->entityField->label,
            component: $this->entityField->form_component,
        )
            ->setType('text')
            ->setHelperText($this->entityField->description)
            ->setDecimalPlaces(data_get($this->entityField->config, 'form.decimal_places', 2))
            ->setThousandSeparator(data_get($this->entityField->config, 'form.thousand_separator', ','))
            ->setDecimalSeparator(data_get($this->entityField->config, 'form.decimal_separator', '.'))
            ->setSuffix(data_get($this->entityField->config, 'form.suffix', '$'))
            ->addInputAttribute([
                'placeholder' => data_get($this->entityField->config, 'form.placeholder')
            ]);
    }

    protected function cast($value, FormElement $element): mixed
    {
        $decimalSeparator = $this->entityField->decimal_seperator ?? '.';
        $thousandSeparator = $this->entityField->thousand_seperator ?? ',';
        $value = str_replace(array($thousandSeparator, $decimalSeparator), array('', '.'), $value);

        return (float) $value;
    }
}
