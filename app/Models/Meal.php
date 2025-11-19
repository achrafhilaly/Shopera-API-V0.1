<?php

namespace App\Models;

use Database\Factories\MealFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\Model;


class Meal extends Model
{
    /** @use HasFactory<MealFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];
}
