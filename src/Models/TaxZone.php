<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use App\Modules\ModuleModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxZone extends ModuleModel
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_tax_zones';

    protected $fillable = [
        'site_id',
        'name',
        'description',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'handling_vat_numbers' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_default) {
                // Ensure only one tax zone can be set as default
                static::where('site_id', $model->site_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'pim_country_pim_tax_zone', 'pim_tax_zone_id', 'pim_country_id');
    }

    public function taxRates()
    {
        return $this->hasMany(TaxRate::class, 'pim_tax_zone_id');
    }

    public function taxCategories()
    {
        return $this->hasMany(TaxCategory::class, 'pim_tax_zone_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
