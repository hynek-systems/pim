<?php

use Hynek\Pim\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['subdomain', 'initialize_site', 'auth', 'admin'])
    ->prefix('admin/products')
    ->group(function () {
        Route::resource('pim.products', ProductController::class);
    });
