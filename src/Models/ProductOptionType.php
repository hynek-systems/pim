<?php

namespace Hynek\Pim\Models;

use App\Models\Entity;
use App\Models\Site;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOptionType extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_product_option_types';

    protected $fillable = [
        'site_id',
        'entity_id',
        'option_type_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function optionType()
    {
        return $this->belongsTo(OptionType::class, 'option_type_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
