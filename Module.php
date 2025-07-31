<?php

namespace Modules\Core\Pim;

use App\EntityField\EntityFieldConfig;
use App\EntityField\EntityFieldManifest;
use App\Exceptions\InvalidEntityField;
use App\Models\EntityType;
use App\Models\Site;
use App\Modules\BaseModule;
use Hynek\Pim\Models\Country;
use Hynek\Pim\Models\Currency;
use Hynek\Pim\Models\TaxCategory;
use Hynek\Pim\Models\TaxRate;
use Hynek\Pim\Models\TaxZone;
use Hynek\Pim\PimServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
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

        $this->addEntityField();
    }

    protected function resolveRelationships()
    {
        parent::resolveRelationships();

        Site::resolveRelationUsing('countries', function ($site) {
            return $site->hasMany(Country::class, 'site_id');
        });

        Site::resolveRelationUsing('currencies', function ($site) {
            return $this->hasMany(Currency::class);
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
        $manifest->config_form = \Hynek\Pim\EntityField\PriceConfigForm::class;
        $manifest->entity_form = \Hynek\Pim\EntityFiels\Entity\PriceEntityForm::class;
        $manifest->config = $config;

        app('entityFieldManager')->addEntityField($manifest);
    }

    public function dependencies(): array
    {
        return [
            'hynek/media' => '0.0.1'
        ];
    }

    protected function installing(): void
    {
        //
    }

    protected function installed(): void
    {
        $productEntityType = $this->registerProductEntityTypes();
        $this->registerProductFields($productEntityType);

        $productVariantEntityType = $this->registerProductVariantEntityType();
        $this->registerProductVariantFields($productVariantEntityType);
    }

    private function registerProductEntityTypes(): EntityType
    {
        return $this->createEntityType(
            __('pim:Product'),
            __('pim:This entity type lets you create product entities.'),
            'product'
        );
    }

    /**
     * @throws InvalidEntityField
     * @throws CircularDependencyException
     * @throws BindingResolutionException
     */
    private function registerProductFields(EntityType $entityType): void
    {
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
            'datepicker',
            __('pim:Available on'),
            'available_on',
            15
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

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Source Owner'),
            'source_owner',
            50,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter source owner'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Source ID'),
            'source_id',
            55,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter source ID'),
                    ],
                ]
            ]
        );
    }

    private function registerProductVariantEntityType(): EntityType
    {
        return $this->createEntityType(
            __('pim:Product Variant'),
            __('pim:This entity type lets you create product variant entities.'),
            'product_variant',
        );
    }

    /**
     * @throws CircularDependencyException
     * @throws InvalidEntityField
     * @throws BindingResolutionException
     */
    private function registerProductVariantFields(EntityType $entityType): void
    {
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
            'text',
            __('pim:For sale'),
            'for_sale',
            35,
            [
                'config' => [
                    'form' => [
                        'type' => 'checkbox',
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Height'),
            'height',
            40,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:cm'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Width'),
            'width',
            45,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:cm'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Length'),
            'length',
            50,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:cm'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Track inventory'),
            'track_inventory',
            55,
            [
                'config' => [
                    'form' => [
                        'type' => 'checkbox',
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Volume'),
            'volume',
            60,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:m³'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Source Owner'),
            'source_owner',
            65,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter source owner'),
                    ],
                ]
            ]
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Source ID'),
            'source_id',
            65,
            [
                'config' => [
                    'form' => [
                        'placeholder' => __('pim:Enter source ID'),
                    ],
                ]
            ]
        );
    }

    protected function uninstalling(): void
    {
        //
    }

    protected function uninstalled(): void
    {
        //
    }

    private function registerMenuItems()
    {
        $root = $this->addMenuItem('admin-nav', 'pim', __('pim:PIM'), 10);
        $this->addMenuItem('admin-nav', 'products', __('pim:Products'), 10, $root);
        $this->addMenuItem('admin-nav', 'countries', __('pim:Countries'), 20, $root);
        $this->addMenuItem('admin-nav', 'currencies', __('pim:Currencies'), 30, $root);
        $this->addMenuItem('admin-nav', 'tax-categories', __('pim:Tax Categories'), 50, $root);
        $this->addMenuItem('admin-nav', 'tax-rates', __('pim:Tax Rates'), 40, $root);
        $this->addMenuItem('admin-nav', 'tax-zones', __('pim:Tax Zones'), 60, $root);
    }
}
