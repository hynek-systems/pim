<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCategory extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_tax_categories';

    protected $fillable = [
        'site_id',
        'name',
        'description',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_default) {
                // Ensure only one tax category can be set as default
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

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
