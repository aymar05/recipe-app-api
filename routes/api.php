<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Dashboard\IngredientController;
use App\Http\Controllers\Dashboard\RecipeController as DashboardRecipeController;
use App\Http\Controllers\Dashboard\RecipeRequestController as DashboardRecipeRequestController;
use App\Http\Controllers\Dashboard\StepController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeRequestController;
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
            Route::get('/', [DashboardRecipeRequestController::class, 'index']);
            Route::get('{id}', [DashboardRecipeRequestController::class, 'show']);
            Route::put('{id}/approve', [DashboardRecipeRequestController::class, 'approve']);
            Route::put('{id}/reject', [DashboardRecipeRequestController::class, 'reject']);
            Route::delete('{id}', [DashboardRecipeRequestController::class, 'destroy']);
        });
    });


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('recipe-requests')->group(function () {
        Route::get('/', [RecipeController::class, 'index']);
        Route::get('{id}', [RecipeController::class, 'show']);

        Route::post('{id}/favorites', [FavoritesController::class, 'store']);

        Route::post('{id}/comments', [CommentController::class, 'store']);
    });


    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoritesController::class, 'index']);
        Route::delete('{id}', [FavoritesController::class, 'destroy']);
    });


    Route::prefix('comments')->group(function () {
        Route::put('{id}', [CommentController::class, 'update']);
        Route::delete('{id}', [CommentController::class, 'destroy']);
    });


    Route::prefix('recipe-requests')->group(function () {
        Route::get('/', [RecipeRequestController::class, 'index']);
        Route::get('{id}', [RecipeRequestController::class, 'show']);
        Route::post('/', [RecipeRequestController::class, 'store']);
    });


    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'updateProfile']);
        Route::put('password', [ProfileController::class, 'updatePassword']);
        Route::post('picture', [ProfileController::class, 'updatePicture']);
    });
});
