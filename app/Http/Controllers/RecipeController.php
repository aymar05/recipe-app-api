<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeFilterRequest;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class RecipeController extends Controller
{
    public function index(RecipeFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(Recipe::class)
                ->with(['steps', 'ingredients', 'comments', 'tags'])
                ->allowedFilters(['name'])
                ->paginate(
                    perPage: $request->input('per_page', 10),
                    page: $request->input('page', 1)
                )
        );
    }

    public function show(int $id): JsonResponse
    {
        $recipe = Recipe::with(['steps', 'ingredients', 'comments', 'tags'])
            ->findOrFail($id);
        return response()->json($recipe);
    }
}
