<?php

namespace Hynek\Pim\Services;

use Hynek\Pim\Models\OptionType;

abstract class OptionTypeService
{
    public static function optionTypesAsSelectOptions(): array
    {
        $optionTypes = OptionType::query()->where('site_id', current_site()->id)->orderBy('position')->get();
        $options = [];
        foreach ($optionTypes as $optionType) {
            $options[$optionType->id] = $optionType->name;
        }
        
        return $options;
    }

    public static function optionTypeValuesAsSelectOptions(OptionType $optionType): array
    {
        $options = [];
        foreach ($optionType->values()->orderBy('position')->get() as $value) {
            $options[$value->id] = $value->name;
        }

        return $options;
    }
}
