<?php

namespace Hynek\Pim\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionTypeValue extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_option_type_values';

    protected $fillable = [
        'site_id',
        'option_type_id',
        'name',
        'presentation',
        'position',
    ];

    protected $casts = [
        'site_id' => 'uuid',
        'option_type_id' => 'uuid',
        'position' => 'integer',
    ];

    public function optionType()
    {
        return $this->belongsTo(OptionType::class, 'option_type_id');
    }

    public function productVariantOptionTypeValues()
    {
        return $this->hasMany(ProductVariantOptionTypeValue::class, 'option_type_value_id');
    }
}
