<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\IngredientController;
use App\Http\Controllers\Dashboard\RecipeController as DashboardRecipeController;
use App\Http\Controllers\Dashboard\RecipeRequestController;
use App\Http\Controllers\Dashboard\StepController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');


Route::prefix('dashboard')
    ->middleware(['auth:sanctum', 'abilities:admin'])
    ->group(function () {
        Route::prefix('recipes')->group(function () {
            Route::get('/', [DashboardRecipeController::class, 'index']);
            Route::post('/', [DashboardRecipeController::class, 'store']);
            Route::put('{id}', [DashboardRecipeController::class, 'update']);
            Route::delete('{id}', [DashboardRecipeController::class, 'destroy']);

            Route::post('{id}/ingredients', [IngredientController::class, 'store']);
            Route::put('ingredients/{id}', [IngredientController::class, 'update']);
            Route::delete('ingredients/{id}', [IngredientController::class, 'destroy']);

            Route::post('{id}/steps', [StepController::class, 'store']);
            Route::put('steps/{id}', [StepController::class, 'update']);
            Route::delete('steps/{id}', [StepController::class, 'destroy']);
        });

        Route::prefix('recipe-requests')->group(function () {
            Route::get('/', [RecipeRequestController::class, 'index']);
            Route::get('{id}', [RecipeRequestController::class, 'show']);
            Route::put('{id}/approve', [RecipeRequestController::class, 'approve']);
            Route::put('{id}/reject', [RecipeRequestController::class, 'reject']);
            Route::delete('{id}', [RecipeRequestController::class, 'destroy']);
        });
    });


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('recipes', [RecipeController::class, 'index']);
    Route::get('recipes/{id}', [RecipeController::class, 'show']);

    Route::get('favorites', [FavoritesController::class, 'index']);
    Route::post('recipes/{id}/favorites', [FavoritesController::class, 'store']);
    Route::delete('favorites/{id}', [FavoritesController::class, 'destroy']);
});
