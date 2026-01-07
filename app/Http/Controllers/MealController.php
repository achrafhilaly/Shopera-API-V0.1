<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $meals = Meal::query();

        // Filter by status
        $meals->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        return MealResource::collection($meals->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request): MealResource
    {
        $data = $request->validated();
        $data['status'] = 'active';
        $meal = Meal::create($data);
        return new MealResource($meal);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal): MealResource
    {
        return new MealResource($meal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal): MealResource
    {
        $meal->update($request->validated());
        return new MealResource($meal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal): Response
    {
        $meal->delete();
        return response()->noContent();
    }

    /**
     * Display a listing of the resource for recipes page.
     */
    public function recipes(Request $request): JsonResponse
    {
        $limit = 9;
        $offset = (int)$request->input('offset', 0);
        $search = $request->input('search');

        $query = Meal::where('status', 'active');

        // Apply search if provided
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Get total count before pagination
        $total = $query->count();

        // Get paginated meals
        $meals = $query->orderByDesc('updated_at')->offset($offset)->take($limit)->get();

        // Calculate pagination metadata
        $totalPages = (int) ceil($total / $limit);
        $currentPage = (int) floor($offset / $limit) + 1;
        $hasNextPage = ($offset + $limit) < $total;
        $hasPreviousPage = $offset > 0;

        return response()->json([
            'data' => MealResource::collection($meals),
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
