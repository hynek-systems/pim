<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pim_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->string('iso_4217');
            $table->string('decimal_marker')->default('.');
            $table->boolean('enabled')->default(true);
            $table->decimal('exchange_rate', 15, 6)->default(1.0);
            $table->string('image_url')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('symbol');
            $table->boolean('symbol_after_amount')->default(true);
            $table->string('thousands_separator')->default(' ');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'iso_4217'], 'currencies_site_id_iso_4217_unique');
            $table->unique(['site_id', 'name'], 'currencies_site_id_name_unique');
            $table->unique(['site_id', 'name', 'iso_4217'], 'currencies_site_id_name_iso_4217_unique');
            $table->index(['site_id', 'enabled'], 'currencies_site_id_enabled_index');
            $table->index(['site_id', 'is_default'], 'currencies_site_id_is_default_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
