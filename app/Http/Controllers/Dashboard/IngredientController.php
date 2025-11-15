<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IngredientController extends Controller
{
    public function store(int $recipeId, IngredientRequest $request): Response
    {
        $recipeExists = Recipe::whereId($recipeId)->exists();
        abort_if(!$recipeExists, 404);
        $data = $request->validated();
        $data['recipe_id'] = $recipeId;
        Ingredient::create($data);
        return response()->noContent();
    }

    public function update(int $id, IngredientRequest $request): Response
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->validated());
        return response()->noContent();
    }

    public function destroy(int $id): Response
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        return response()->noContent();
    }
}
