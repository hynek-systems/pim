<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('entities')->onDelete('cascade');
            $table->foreignUuid('entity_id')->constrained('entities')->onDelete('cascade');
            $table->boolean('is_master')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'product_id', 'entity_id'], 'pim_product_variant_unique');
            $table->index(['site_id'], 'pim_product_variant_site_id_index');
            $table->index(['product_id'], 'pim_product_variant_product_id_index');
            $table->index(['entity_id'], 'pim_product_variant_entity_id_index');
            $table->index(['site_id', 'product_id'], 'pim_product_variant_site_product_index');
            $table->index(['site_id', 'entity_id'], 'pim_product_variant_site_entity_index');
            $table->index(['product_id', 'entity_id'], 'pim_product_variant_product_entity_index');
            $table->index(['site_id', 'is_master'], 'pim_product_variant_site_is_master_index');
            $table->index(['product_id', 'is_master'], 'pim_product_variant_product_is_master_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_product_variants');
    }
};
