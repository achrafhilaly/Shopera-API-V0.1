<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * Display a listing of the resource for express shop page.
     */
    public function expressShop(Request $request): JsonResponse
    {
        $offset = (int)$request->input('offset', 0);
        $limit = 8;
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Product::where('status', 'active');

        // Apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Apply Category filter if provided and not "all"
        if ($category && $category !== 'all') {
            // Convert slug format (protein_bars) to name format (protein bars)
            $categoryName = str_replace('+', ' ', $category);
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        // Get total count before pagination
        $total = $query->count();

        // Get paginated products
        $products = $query->with([
                'category' => function ($query) {
                    $query->select('_id', 'name');
                }
            ])
            ->orderByDesc('updated_at')
            ->offset($offset)
            ->take($limit)
            ->get();

        // Calculate pagination metadata
        $totalPages = (int) ceil($total / $limit);
        $currentPage = (int) floor($offset / $limit) + 1;
        $hasNextPage = ($offset + $limit) < $total;
        $hasPreviousPage = $offset > 0;

        return response()->json([
            'data' => ProductResource::collection($products),
            'pagination' => [
                'total' => $total,
                'per_page' => $limit,
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'offset' => $offset,
                'has_next_page' => $hasNextPage,
                'has_previous_page' => $hasPreviousPage,
            ],
        ]);
    }
}
