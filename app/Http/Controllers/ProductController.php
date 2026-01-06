<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with([
            'category' => function ($query) {
                $query->select('_id', 'name');
            }
        ])->orderByDesc('updated_at')->get();

        return ProductResource::collection($products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->validated());
        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductResource
    {
        $product = $product->load([
            'category' => function ($query) {
                $query->select('id', 'name');
            }
        ]);
        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());
        return ProductResource::make($product->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();
        return response()->noContent();
    }

        /**
     * Display a listing of the resource for home page.
     */
    public function home(): AnonymousResourceCollection
    {
        $products = Product::where('status', 'active')
        ->with([
            'category' => function ($query) {
                $query->select('_id', 'name');
            }
        ])
        ->orderByDesc('updated_at')->take(4)->get();

        return ProductResource::collection($products);
    }
}
