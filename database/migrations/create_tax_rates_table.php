<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_tax_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignUuid('pim_tax_zone_id')->constrained('pim_tax_zones')->onDelete('cascade');
            $table->foreignUuid('pim_tax_category_id')->constrained('pim_tax_categories')->onDelete('cascade');
            $table->string('name');
            $table->decimal('rate', 10, 5)->default(0.00);
            $table->boolean('included_in_price')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'pim_tax_zone_id', 'pim_tax_category_id'], 'pim_tax_rates_unique');
            $table->index(['site_id', 'pim_tax_zone_id'], 'pim_tax_rates_site_id_tax_zone_id_index');
            $table->index(['site_id', 'pim_tax_category_id'], 'pim_tax_rates_site_id_tax_category_id_index');
            $table->index(['pim_tax_zone_id', 'pim_tax_category_id'],
                'pim_tax_rates_tax_zone_id_tax_category_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_tax_rates');
    }
};
