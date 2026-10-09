<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->when(filled($request->q), function ($query) use ($request) {
                $like = '%'.addcslashes(trim($request->q), '\\%_').'%';

                $query->where(function ($q) use ($like) {
                    //case in-sensitive search
                    $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) COLLATE utf8mb4_unicode_ci LIKE ?", [$like])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) COLLATE utf8mb4_unicode_ci LIKE ?", [$like])
                    ->orWhere('sku', 'like', $like);
                });
            })
            ->when($request->category, fn ($query, $c) => $query->where('category_id', $c))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('dashboard.products.create', [
            'product' => new Product(),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::create($this->payload($request));
            $this->syncImages($product, $request);

            return $product;
        });

        return redirect()->route('dashboard.products.edit', $product)
            ->with('status', __('admin.product_saved'));
    }

    public function edit(Product $product)
    {
        return view('dashboard.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($this->payload($request));
            $this->syncImages($product, $request);
        });

        return redirect()->route('dashboard.products.edit', $product)
            ->with('status', __('admin.product_saved'));
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete, images stay so it can be restored later

        return redirect()->route('dashboard.products.index')
            ->with('status', __('admin.product_deleted'));
    }

    private function payload(ProductRequest $request): array
    {
        $data = $request->validated();

        return [
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'description' => array_filter($data['description'] ?? [], 'filled'),
            'sku'         => $data['sku'] ?? null,
            'price'       => isset($data['price']) ? (int) round($data['price'] * 100) : null,
            'specs'       => $this->specs($data['specs'] ?? []),
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    private function specs(array $rows): ?array
    {
        $rows = collect($rows)
            ->filter(fn ($r) => filled($r['key'] ?? null))
            ->map(fn ($r) => ['key' => trim($r['key']), 'value' => trim($r['value'] ?? '')])
            ->values()
            ->all();

        return $rows ?: null;
    }

    private function syncImages(Product $product, ProductRequest $request): void
    {
        // 1. remove ticked images (model events delete the files)
        $product->images()->whereIn('id', $request->input('remove_images', []))->get()->each->delete();

        // 2. add new uploads after the current last position
        $order = (int) $product->images()->max('sort_order');
        foreach ($request->file('images', []) as $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
                'sort_order' => ++$order,
            ]);
        }

        // 3. exactly one primary image: chosen one, else the existing one, else the first
        $images = $product->images()->get();
        $primary = $images->firstWhere('id', (int) $request->input('primary_image'))
            ?? $images->firstWhere('is_primary', true)
            ?? $images->first();

        $images->each(fn ($image) => $image->update(['is_primary' => $primary && $image->is($primary)]));
    }
}
