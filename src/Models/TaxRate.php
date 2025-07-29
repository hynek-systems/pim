<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_tax_rates';

    protected $fillable = [
        'site_id',
        'pim_tax_zone_id',
        'pim_tax_category_id',
        'name',
        'rate',
        'included_in_price',
    ];

    protected $casts = [
        'rate' => 'decimal:5',
        'included_in_price' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function taxZone()
    {
        return $this->belongsTo(TaxZone::class, 'pim_tax_zone_id');
    }

    public function taxCategory()
    {
        return $this->belongsTo(TaxCategory::class, 'pim_tax_category_id');
    }
}
