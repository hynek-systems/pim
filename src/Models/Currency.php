<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_currencies';

    protected $fillable = [
        'site_id',
        'name',
        'iso_4217',
        'decimal_marker',
        'enabled',
        'exchange_rate',
        'image_url',
        'is_default',
        'symbol',
        'symbol_after_amount',
        'thousands_separator'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'is_default' => 'boolean',
        'symbol_after_amount' => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_default) {
                // Ensure only one default currency per site
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
}
