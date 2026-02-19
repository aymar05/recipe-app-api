<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeFilterRequest;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(RecipeFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(Recipe::class)
                ->with(['steps', 'ingredients', 'comments.user', 'tags'])
                ->allowedFilters(['name', AllowedFilter::exact('tags', 'tags.name')])
                ->latest()
                ->paginate(
                    perPage: $request->input('per_page', 10),
                    page: $request->input('page', 1)
                )
        );
    }

    public function search(Request $request): JsonResponse
    {
        $searchQuery = $request->input('query');

        if (empty($searchQuery)) {
            return response()->json([
                'data' => [],
                'meta' => ['total' => 0]
            ]);
        }

        // Utilisation du scope 'searchRecipes' défini dans le Modèle
        $recipes = Recipe::searchRecipes($searchQuery)
            ->with(['steps', 'ingredients', 'comments.user', 'tags'])
            ->latest()
            ->paginate(10);

        return response()->json($recipes);
    }



    public function show(int $id): JsonResponse
    {
        $recipe = Recipe::with(['steps', 'ingredients', 'comments.user', 'tags'])
            ->findOrFail($id);
        return response()->json($recipe);
    }
}
