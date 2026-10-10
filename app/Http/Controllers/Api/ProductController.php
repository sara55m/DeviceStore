<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Requests\Api\Products\IndexRequest;

class ProductController extends Controller
{
    public function index(IndexRequest $request){

        $sort = $request->input('sort', 'newest');

        $products=Product::with(['category','primaryImage'])
        ->whereNull('deleted_at')
        ->where('is_active',true)
        ->search($request->input('search'))
        ->when($request->filled('category'), fn ($q) => $q->whereHas(
            'category', fn ($c) => $c->where('slug', $request->input('category'))
        ))
        ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
        // products without a price (quote only) always go last
        ->when($sort === 'price_asc', fn ($q) => $q->orderByRaw('price IS NULL')->orderBy('price'))
        ->when($sort === 'price_desc', fn ($q) => $q->orderByRaw('price IS NULL')->orderByDesc('price'))
        ->orderByDesc('id')   // newest first, and a stable order between pages
        ->paginate($request->integer('per_page', 12))
        ->withQueryString();

        return response()->json([
            "messages"=>__("user.products_returned_successfully"),
            "products"=>ProductResource::collection($products)
        ],200);
    }

    public function show(string $slug)
    {
        $product = Product::where('is_active',true)
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->first();

        abort_if(! $product, 404, __('product.not_found'));

        return response()->json([
            "messages"=>__("user.product_returned_successfully"),
            "product"=>new ProductDetailResource($product)
        ],200);

    }
}
