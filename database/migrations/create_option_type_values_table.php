<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_option_type_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreignUuid('option_type_id')->references('id')->on('pim_option_types')->onDelete('cascade');
            $table->string('name');
            $table->string('presentation')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'option_type_id', 'name'], 'unique_site_option_type_name');
            $table->index(['site_id', 'option_type_id'], 'index_site_option_type_id');
            $table->index(['site_id', 'position'], 'index_site_position');
            $table->index(['site_id', 'name'], 'index_site_name');
        });

        Schema::create('pim_product_variant_option_type_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreignUuid('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->foreignUuid('product_variant_id')->references('id')->on('pim_product_variants')->onDelete('cascade');
            $table->foreignUuid('option_type_id')->references('id')->on('pim_option_types')->onDelete('cascade');
            $table->foreignUuid('option_type_value_id')->references('id')->on('pim_option_type_values')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'entity_id', 'option_type_value_id'], 'unique_site_entity_option_type_value');
            $table->unique(['site_id', 'product_variant_id', 'option_type_value_id'],
                'unique_site_product_variant_option_type_value');
            $table->unique(['site_id', 'product_variant_id', 'option_type_id'],
                'unique_site_product_variant_option_type');
            $table->index(['site_id', 'entity_id'], 'index_site_entity_id');
            $table->index(['site_id', 'product_variant_id'], 'index_site_product_variant_id');
            $table->index(['site_id', 'option_type_id'], 'index_site_option_type_id');
            $table->index(['site_id', 'option_type_value_id'], 'index_site_option_type_value_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_option_type_values');
    }
};
