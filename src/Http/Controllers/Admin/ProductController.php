<?php

namespace Hynek\Pim\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\PaginatedJsonResponse;
use Hynek\Pim\Products\CreateQueryBuilder;
use Hynek\Pim\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductService::site_products_query();
        $queryBuilder = app(CreateQueryBuilder::class, ['defaultSort' => 'created_at']);
        $products = $queryBuilder($query->getQuery());
        $products->paginate()->appends($request->query());

        if ($request->expectsJson()) {
            return PaginatedJsonResponse::make($products);
        }

        return view('pim::admin.products.index', compact('products'));
    }
}
