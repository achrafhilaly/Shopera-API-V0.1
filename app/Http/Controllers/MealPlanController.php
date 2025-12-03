<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealPlanBuildRequest;
use App\Http\Requests\StoreMealPlanRequest;
use App\Http\Requests\UpdateMealPlanRequest;
use App\Http\Resources\MealPlanResource;
use App\Models\MealPlan;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MealPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $mealPlans = MealPlan::all();
        return MealPlanResource::collection($mealPlans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealPlanRequest $request): MealPlanResource
    {
        $data = $request->validated();
        $data['status'] = 'inactive';
        $mealPlan = MealPlan::create($data);
        return new MealPlanResource($mealPlan);
    }

    /**
     * Display the specified resource.
     */
    public function show(MealPlan $mealPlan): MealPlanResource
    {
        return new MealPlanResource($mealPlan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealPlanRequest $request, MealPlan $mealPlan): MealPlanResource
    {
        $mealPlan->update($request->validated());
        return new MealPlanResource($mealPlan->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealPlan $mealPlan): Response
    {
        $mealPlan->delete();
        return response()->noContent();
    }

    /**
     * Store a newly created meal plan build in storage.
     */
    public function storeBuild(MealPlan $mealPlan, StoreMealPlanBuildRequest $request): MealPlanResource
    {
        $data = $request->validated();
        $data['status'] = 'active';
        $mealPlan->update($data);
        return new MealPlanResource($mealPlan->refresh());
    }
}
