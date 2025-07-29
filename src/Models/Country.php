<?php

namespace Hynek\Pim\Models;

use App\Modules\ModuleModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends ModuleModel
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_countries';

    protected $fillable = [
        'site_id',
        'enabled',
        'is_default',
        'name',
        'iso_name',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo('App\Models\Site');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
