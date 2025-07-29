<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->boolean('is_default')->default(false);
            $table->string('name');
            $table->string('iso_name');
            $table->timestamps();

            $table->index(['site_id', 'enabled'], 'pim_countries_site_id_enabled_index');
            $table->index(['site_id', 'iso_name'], 'pim_countries_site_id_iso_name_index');
            $table->index(['site_id', 'name'], 'pim_countries_site_id_name_index');
            $table->unique(['site_id', 'iso_name'], 'pim_countries_site_id_iso_name_unique');
            $table->unique(['site_id', 'name'], 'pim_countries_site_id_name_unique');
            $table->unique(['site_id', 'enabled', 'name'], 'pim_countries_site_id_enabled_name_unique');
            $table->unique(['site_id', 'enabled', 'iso_name'], 'pim_countries_site_id_enabled_iso_name_unique');
            $table->unique(['site_id', 'enabled', 'name', 'iso_name'],
                'pim_countries_site_id_enabled_name_iso_name_unique');
            $table->unique(['site_id', 'name', 'iso_name'], 'pim_countries_site_id_name_iso_name_unique');
            $table->unique(['site_id', 'is_default'], 'pim_countries_site_id_is_default_unique');
            $table->unique(['site_id', 'enabled', 'is_default'], 'pim_countries_site_id_enabled_is_default_unique');
            $table->unique(['site_id', 'name', 'is_default'], 'pim_countries_site_id_name_is_default_unique');
            $table->unique(['site_id', 'iso_name', 'is_default'], 'pim_countries_site_id_iso_name_is_default_unique');
            $table->unique(['site_id', 'enabled', 'name', 'is_default'],
                'pim_countries_site_id_enabled_name_is_default_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_countries');
    }
};
