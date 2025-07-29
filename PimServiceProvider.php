<?php

namespace Hynek\Pim;

use Hynek\PackageTools\Package;
use Hynek\PackageTools\PackageToolsServiceProvider;

class PimServiceProvider extends PackageToolsServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('pim')
            ->hasMigrations([
                'create_countries_table',
                'create_tax_categories_table',
                'create_tax_rates_table',
                'create_tax_zones_table',
                'create_product_variants_table',
            ]);
    }
}
