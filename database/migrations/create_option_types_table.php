<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_option_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('presentation')->nullable();
            $table->string('type')->default('select'); // 'select', 'radio', 'checkbox', etc.
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug'], 'unique_site_slug');
            $table->unique(['site_id', 'name'], 'index_site_name');
            $table->index(['site_id', 'is_required'], 'index_site_is_required');
            $table->index(['site_id', 'is_active'], 'index_site_is_active');
            $table->index(['site_id', 'position'], 'index_site_position');
        });

        Schema::create('pim_product_option_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreignUuid('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->foreignUuid('option_type_id')->references('id')->on('pim_option_types')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'entity_id', 'option_type_id'], 'unique_site_entity_option_type');
            $table->index(['site_id', 'entity_id'], 'index_site_entity_id');
            $table->index(['site_id', 'option_type_id'], 'index_site_option_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pim_option_types');
    }
};
