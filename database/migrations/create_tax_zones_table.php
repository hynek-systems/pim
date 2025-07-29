<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_tax_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('handling_vat_numbers')->default(false);
            $table->timestamps();

            $table->unique(['site_id', 'name']);
            $table->index(['site_id'], 'pim_tax_zones_site_id_index');
            $table->index(['site_id', 'handling_vat_numbers'], 'pim_tax_zones_handling_vat_numbers_index');
            $table->index(['site_id', 'is_default'], 'pim_tax_zones_is_default_index');
        });

        Schema::create('pim_country_pim_tax_zone', function (Blueprint $table) {
            $table->foreignUuid('pim_country_id')->constrained('pim_countries')->onDelete('cascade');
            $table->foreignUuid('pim_tax_zone_id')->constrained('pim_tax_zones')->onDelete('cascade');
            $table->primary(['pim_tax_zone_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_tax_zones');
        Schema::dropIfExists('pim_country_pim_tax_zone');
    }
};
