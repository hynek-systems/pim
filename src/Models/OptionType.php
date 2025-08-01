<?php

namespace Hynek\Pim\Models;

use App\Models\Site;
use App\Modules\ModuleModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OptionType extends ModuleModel
{
    use HasUuids, SoftDeletes;

    protected $table = 'pim_option_types';

    protected $fillable = [
        'site_id',
        'name',
        'slug',
        'presentation',
        'type',
        'is_required',
        'is_active',
        'position'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function productOptionTypes()
    {
        return $this->hasMany(ProductOptionType::class, 'option_type_id');
    }

    public function values()
    {
        return $this->hasMany(OptionTypeValue::class, 'option_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }
}
