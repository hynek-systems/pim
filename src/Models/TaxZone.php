<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxZone extends Model
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
