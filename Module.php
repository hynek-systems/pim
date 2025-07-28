<?php

namespace Modules\Core\Pim;

use App\EntityField\EntityFieldConfig;
use App\EntityField\EntityFieldManifest;
use App\Models\EntityType;
use App\Modules\BaseModule;
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
        //
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
            1,
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
            2,
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
            3
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:SKU'),
            'sku',
            4
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Barcode'),
            'barcode',
            5
        );

        $this->createEntityField(
            $entityType,
            'text',
            __('pim:Weight'),
            'weight',
            6,
            [
                'config' => [
                    'form' => [
                        'type' => 'number',
                        'suffix' => __('pim:Kg'),
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
