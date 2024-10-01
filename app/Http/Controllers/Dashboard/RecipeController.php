<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeCreationRequest;
use App\Http\Requests\RecipeFilterRequest;
use App\Http\Requests\RecipeUpdateRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeStep;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class RecipeController extends Controller
{
    public function index(RecipeFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(Recipe::class)
                ->with(['steps', 'ingredients', 'comments', 'tags'])
                ->allowedFilters(['name', AllowedFilter::exact('tags', 'tags.name')])
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

    /**
     * @throws Throwable
     */
    public function store(RecipeCreationRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $data = $request->validated();
        $data['image'] = $request->file('image')
            ->storePublicly(Recipe::IMAGE_FOLDER);
        $recipe = Recipe::create($data);
        if ($request->filled('steps')) {
            foreach ($request->input('steps') as $step) {
                RecipeStep::create([
                    'recipe_id'   => $recipe->id,
                    'name'        => $step['name'],
                    'description' => $step['description'],
                    'duration'    => $step['duration'],
                ]);
            }
        }

        if ($request->filled('ingredients')) {
            foreach ($request->input('ingredients') as $ingredient) {
                Ingredient::create([
                    'recipe_id' => $recipe->id,
                    'name'      => $ingredient['name'],
                    'quantity'  => $ingredient['quantity'],
                    'measure'   => $ingredient['measure'],
                ]);
            }
        }

        if ($request->filled('tags')) {
            $tagsData = $request->input('tags');
            $tags = array_map(fn($tag) => ['name' => $tag], $tagsData);
            Tag::upsert($tags, ['name'], ['name']);
            $tagIds = Tag::whereIn('name', $tagsData)
                ->pluck('id');
            $recipe->tags()->sync($tagIds);
        }

        DB::commit();
        $recipe->load(['steps', 'ingredients', 'tags']);
        return response()->json($recipe);
    }

    public function update(int $id, RecipeUpdateRequest $request): JsonResponse
    {
        /** @var Recipe $recipe */
        $recipe = Recipe::with(['steps', 'ingredients', 'comments', 'tags'])
            ->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->storePublicly(Recipe::IMAGE_FOLDER);
        }

        if ($request->filled('tags')) {
            $tagsData = $request->input('tags');
            $tags = array_map(fn($tag) => ['name' => $tag], $tagsData);
            Tag::upsert($tags, ['name'], ['name']);
            $tagIds = Tag::whereIn('name', $tagsData)
                ->pluck('id');
            $recipe->tags()->sync($tagIds);
        }

        $recipe->update($data);
        return response()->json($recipe);
    }

    public function destroy(int $id): Response
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return response()->noContent();
    }
}
