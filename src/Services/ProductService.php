<?php

namespace Hynek\Pim\Services;

use App\Models\EntityType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductService
{
    public static function site_products(): Collection
    {
        return current_site()?->products;
    }

    public static function site_products_query(): HasMany
    {
        return current_site()?->products();
    }

    public static function get_entity_type(): EntityType
    {
        return EntityType::query()
            ->where('uri', 'products')
            ->firstOrFail();
    }
}
