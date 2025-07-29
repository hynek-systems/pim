<?php

namespace Hynek\Pim\Models;

use App\Models\Entity;
use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_product_variants';

    protected $fillable = [
        'site_id',
        'product_id',
        'entity_id',
        'is_master',
    ];

    protected $casts = [
        'is_master' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function product()
    {
        return $this->belongsTo(Entity::class, 'product_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function scopeIsMaster($query)
    {
        return $query->where('is_master', true);
    }

    public function scopeIsNotMaster(Builder $query)
    {
        return $query->where('is_master', false);
    }
}
