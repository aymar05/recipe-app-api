<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeFilterRequest;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FavoritesController extends Controller
{
    public function index(RecipeFilterRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        return response()->json(
            QueryBuilder::for($user->favorites())
                ->with(['steps', 'ingredients', 'comments', 'tags'])
                ->allowedFilters(['name', AllowedFilter::exact('tags', 'tags.name')])
                ->paginate(
                    perPage: $request->input('per_page', 10),
                    page: $request->input('page', 1)
                )
        );
    }

    public function store(int $recipeId, Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $inFavorites = Favorite::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->exists();

        abort_if($inFavorites, 400);
        Favorite::create([
            'user_id'   => $user->id,
            'recipe_id' => $recipeId
        ]);
        return response()->noContent();
    }

    public function destroy(int $recipeId, Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->firstOrFail();
        $favorite->delete();
        return response()->noContent();
    }
}
