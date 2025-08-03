<?php

namespace Hynek\Pim\Services;

use Hynek\Pim\Models\OptionType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Context;

abstract class OptionTypeService
{
    /**
     * Get all option types as an associative array for select options.
     *
     * @return array
     */
    public static function optionTypesAsSelectOptions(): array
    {
        $optionTypes = OptionType::query()->where('site_id', current_site()->id)->orderBy('position')->get();
        $options = [];
        foreach ($optionTypes as $optionType) {
            $options[$optionType->id] = $optionType->name;
        }

        return $options;
    }

    /**
     * Get option type values as an associative array for select options.
     *
     * @param  OptionType  $optionType
     * @return array
     */
    public static function optionTypeValuesAsSelectOptions(OptionType $optionType): array
    {
        $options = [];
        foreach ($optionType->values()->orderBy('position')->get() as $value) {
            $options[$value->id] = $value->name;
        }

        return $options;
    }

    /**
     * Get option types for the current product.
     *
     * @return Collection|null
     */
    public static function optionTypesForCurrentProduct(): ?Collection
    {
        $product = Context::get('product');
        if (!$product) {
            return null;
        }

        return OptionType::query()
            ->where('site_id', current_site()->id)
            ->whereHas('values', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->orderBy('position')
            ->get();
    }
}
