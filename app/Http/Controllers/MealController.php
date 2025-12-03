<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

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
}
