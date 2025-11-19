<?php

namespace App\Models;

use Database\Factories\MealPlanFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\Model;

class MealPlan extends Model
{
    /** @use HasFactory<MealPlanFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];
}
