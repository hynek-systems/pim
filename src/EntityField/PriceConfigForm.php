<?php

namespace Hynek\Pim\EntityField;

use App\Contracts\EntityField\ConfigForm;
use App\Contracts\Form\FormBuilder;
use App\Form\ButtonCollection;
use App\Form\Controls\TextInput;
use App\Form\FormElementCollection;
use Illuminate\Support\Collection;

class PriceConfigForm implements ConfigForm
{
    public function __construct(
        protected array $field,
        protected FormBuilder $builder,
    ) {
    }

    public function getElements(): ?FormElementCollection
    {
        return new FormElementCollection([
            TextInput::make('config.form.placeholder'.__('Placeholder'))
                ->setHelperText(__('Value of the placeholder attribute.'))
                ->setValue(
                    data_get($this->field, 'form.placeholder',
                        config('entity-fields.fields.price.config.form.placeholder'))
                )
                ->setPosition(50),

            TextInput::make('config.form.decimal_places', __('pim:Decimal places'))
                ->setHelperText(__('Number of decimal places for the price field. Default: 2'))
                ->setType('number')
                ->setValue(
                    data_get($this->field, 'form.decimal_places',
                        config('entity-fields.fields.price.config.form.decimal_places', 2))
                )
                ->setPosition(55),

            TextInput::make('config.form.thousand_separator', __('pim:Thousand separator'))
                ->setHelperText(__('Character used to separate thousands. Default: ,'))
                ->setValue(
                    data_get($this->field, 'form.thousand_separator',
                        config('entity-fields.fields.price.config.form.thousand_separator', ','))
                )
                ->setPosition(60),

            TextInput::make('config.form.decimal_separator', __('pim:Decimal separator'))
                ->setHelperText(__('Character used to separate decimals. Default: .'))
                ->setValue(
                    data_get($this->field, 'form.decimal_separator',
                        config('entity-fields.fields.price.config.form.decimal_separator', '.'))
                )
                ->setPosition(65),

            TextInput::make('confi.form-suffix', __('pim:Currency suffix'))
                ->setHelperText(__('Suffix to be added after the price. Default: $'))
                ->setValue(
                    data_get($this->field, 'form.suffix',
                        config('entity-fields.fields.price.config.form.suffix', '$'))
                )
                ->setPosition(70),

            TextInput::make('config.rules.required', __('Required'))
                ->setType('checkbox')
                ->setHelperText(__('Check this if you want the field to be required.'))
                ->setValue(
                    data_get($this->field, 'rules.required',
                        config('entity-fields.fields.price.config.rules.required', false))
                )
                ->setPosition(75),
        ]);
    }

    public function getButtons(): ?ButtonCollection
    {
        return null;
    }

    public function getRules(): ?Collection
    {
        return collect([
            'config.form.placeholder' => 'nullable|string|max:255',
            'config.form.decimal_places' => 'nullable|integer|min:0|max:10',
            'config.form.thousand_separator' => 'nullable|string|max:1',
            'config.form.decimal_separator' => 'nullable|string|max:1',
            'config.form.suffix' => 'nullable|string|max:10',
            'config.rules.required' => 'boolean',
        ]);
    }
}
