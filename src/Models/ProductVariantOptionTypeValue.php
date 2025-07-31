<?php

namespace Hynek\Pim\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantOptionTypeValue extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_product_variant_option_type_values';

    protected $fillable = [
        'site_id',
        'entity_id',
        'product_variant_id',
        'option_type_id',
        'option_type_value_id',
    ];
}
