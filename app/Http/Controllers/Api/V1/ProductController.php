<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::active()
            ->with(['category', 'variants', 'media'])
            ->when($request->category_id, fn ($q, $c) => $q->where('category_id', $c))
            ->when($request->search, fn ($q, $s) => $q->whereRaw("JSON_EXTRACT(name, '$.".app()->getLocale()."') LIKE ?", ["%{$s}%"]))
            ->when($request->featured, fn ($q) => $q->featured())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return ProductResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'variants', 'media']);

        return new ProductResource($product);
    }
}
