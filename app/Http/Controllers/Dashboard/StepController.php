<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StepRequest;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Http\Response;

class StepController extends Controller
{
    public function store(int $recipeId, StepRequest $request): Response
    {
        $recipeExists = Recipe::whereId($recipeId)->exists();
        abort_if(!$recipeExists, 404);
        $data = $request->validated();
        $data['recipe_id'] = $recipeId;
        RecipeStep::create($data);
        return response()->noContent();
    }

    public function update(int $stepId, StepRequest $request): Response
    {
        $recipeStep = RecipeStep::findOrFail($stepId);
        $recipeStep->update($request->validated());
        return response()->noContent();
    }

    public function destroy(int $stepId): Response
    {
        $recipeStep = RecipeStep::findOrFail($stepId);
        $recipeStep->delete();
        return response()->noContent();
    }
}
