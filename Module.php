<?php

namespace Modules\Core\Pim;

use App\EntityField\EntityFieldConfig;
use App\EntityField\EntityFieldManifest;
use App\Models\EntityType;
use App\Models\Site;
use App\Modules\BaseModule;
use Hynek\Pim\Models\Country;
use Hynek\Pim\Models\TaxCategory;
use Hynek\Pim\Models\TaxRate;
use Hynek\Pim\Models\TaxZone;
use Hynek\Pim\PimServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class Module extends BaseModule
{
    public function name(): string
    {
        return Str::headline(class_basename($this));
    }

    public function description(): string
    {
        return file_get_contents(__DIR__.'/readme.md');
    }

    public function version(): string
    {
        return $this->provider()->getVersion();
    }

    public function provider(): ServiceProvider
    {
        return app(PimServiceProvider::class);
    }

    public function bootstrap(): void
    {
        $this->resolveRelationships();
    }

    protected function resolveRelationships()
    {
        parent::resolveRelationships();

        Site::resolveRelationUsing('countries', function ($site) {
            return $site->hasMany(Country::class, 'site_id');
        });

        Site::resolveRelationUsing('taxCategories', function ($site) {
            return $site->hasMany(TaxCategory::class, 'site_id');
        });

        Site::resolveRelationUsing('taxRates', function ($site) {
            return $site->hasMany(TaxRate::class, 'site_id');
        });

        Site::resolveRelationUsing('taxZones', function ($site) {
            return $site->hasMany(TaxZone::class, 'site_id');
        });
    }

    protected function installing(): void
    {
        //
    }

    protected function installed(): void
    {
        $productEntityType = $this->registerProductEntityTypes();
        $this->registerProductFields($productEntityType);
    }

    private function registerProductEntityTypes(): EntityType
    {
        return $this->createEntityType(
            __('pim:Product'),
            __('pim:This entity type lets you create product entities.'),
            'product'
        );
    }

    private function registerProductFields(EntityType $entityType): void
    {
        $this->addEntityField();

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Web URL'),
            'uri',
            0,
            [
                'config' => [
                    'form' => [
                        'type' => 'url',
                        'placeholder' => __('pim:Enter product URL'),
                    ]
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Position'),
            'position',
            5,
            [
                'config' => [
                    'type' => 'number',
                    'form' => [
                        'placeholder' => __('pim:Enter position'),
                    ],
                ],
            ]
        );

        $this->createEntityField(
            $entityType,
            'price',
            __('pim:Master Price'),
            'master[price]',
            10,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter master price'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'datepicker',
            __('pim:Available on'),
            'available_on',
            15
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:SKU'),
            'sku',
            20
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Barcode'),
            'barcode',
            25
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Weight'),
            'weight',
            30,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:Kg'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'select',
            __('Tax Category'),
            'tax_category',
            35,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Select tax category'),
                        'options' => current_site()?->taxCategories->pluck('name', 'id')->toArray(),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'wysywig',
            __('pim:Short Description'),
            'short_description',
            40,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter short description'),
                        'rows' => 4,
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'wysywig',
            __('pim:Description'),
            'description',
            45,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter description'),
                        'rows' => 10,
                    ],
                ]
            ]
        );
    }

    private function addEntityField()
    {
        $config = app(EntityFieldConfig::class);
        $config->setFormConfig([
            'placeholder' => 'Enter price here',
            'decimal_places' => 2,
            'thousand_separator' => ' ',
        ]);
        $config->setRulesConfig(['required' => true, 'numeric' => true]);

        $manifest = app(EntityFieldManifest::class, ['key' => 'price']);
        $manifest->label = 'Price';
        $manifest->description = 'A field for entering price.';
        $manifest->form_component = null;
        $manifest->render_component = 'pim::entity-fields.renderers.price';
        $manifest->config_form = \Hynek\Pim\EntityFiels\PriceConfigForm::class;
        $manifest->entity_form = \Hynek\Pim\EntityFiels\Entity\PriceEntityForm::class;
        $manifest->config = $config;

        app('entityFieldManager')->addEntityField($manifest);
    }

    protected function uninstalling(): void
    {
        //
    }

    protected function uninstalled(): void
    {
        //
    }
}
