<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequestFilterRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeRequest;
use App\Models\RecipeRequestIngredient;
use App\Models\RecipeRequestStep;
use App\Models\RecipeRequestTag;
use App\Models\RecipeStep;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RecipeRequestController extends Controller
{
    public function index(RecipeRequestFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(RecipeRequest::class)
                ->with(['steps', 'ingredients', 'recipe', 'user'])
                ->allowedFilters(['name', AllowedFilter::exact('status')])
                ->paginate(
                    perPage: $request->input('per_page', 10),
                    page: $request->input('page', 1)
                )
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(RecipeRequest::with(['steps', 'recipe', 'user', 'ingredients'])
            ->findOrFail($id));
    }

    public function reject(int $id): Response
    {
        $recipe = RecipeRequest::where('status', RequestStatus::Pending)
            ->findOrFail($id);
        $recipe->status = RequestStatus::Rejected;
        $recipe->save();
        return response()->noContent();
    }

    /**
     * @throws \Throwable
     */
    public function approve(int $id): Response
    {
        DB::beginTransaction();
        $recipeRequest = RecipeRequest::where('status', RequestStatus::Pending)
            ->findOrFail($id);
        $recipeRequest->status = RequestStatus::Approved;
        $recipe = Recipe::create([
            'title' => $recipeRequest->title,
            'image' => $recipeRequest->image,
            'time'  => $recipeRequest->preparation_time,
        ]);

        $recipeRequest->steps()->each(function (RecipeRequestStep $step) use ($recipe) {
            RecipeStep::create([
                'name'        => $step->name,
                'description' => $step->description,
                'duration'    => $step->duration,
                'recipe_id'   => $recipe->id
            ]);
        });

        $recipeRequest->ingredients()->each(function (RecipeRequestIngredient $ingredient) use ($recipe) {
            Ingredient::create([
                'name'      => $ingredient->name,
                'quantity'  => $ingredient->quantity,
                'measure'   => $ingredient->measure,
                'recipe_id' => $recipe->id,
            ]);
        });

        $tags = $recipeRequest
            ->tags()
            ->get()
            ->map(fn(RecipeRequestTag $tag) => ['name' => $tag->name])
            ->toArray();
        Tag::upsert($tags, ['name'], ['name']);
        $tagIds = Tag::whereIn('name', $tags)
            ->pluck('id');
        $recipe->tags()->sync($tagIds);
        $recipeRequest->save();
        DB::commit();
        return response()->noContent();
    }

    public function destroy(int $id): Response
    {
        $recipe = RecipeRequest::findOrFail($id);
        $recipe->delete();
        return response()->noContent();
    }
}
