<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeRequestCreationRequest;
use App\Http\Requests\RecipeRequestFilterRequest;
use App\Models\Recipe;
use App\Models\RecipeRequest;
use App\Models\RecipeRequestIngredient;
use App\Models\RecipeRequestStep;
use App\Models\RecipeRequestTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RecipeRequestController extends Controller
{
    public function index(RecipeRequestFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(RecipeRequest::class)
                ->with(['steps', 'recipe'])
                ->where('user_id', $request->user()->id)
                ->allowedFilters(['name', AllowedFilter::exact('status')])
                ->paginate(
                    perPage: $request->input('per_page', 1),
                    page: $request->input('page', 10)
                )
        );
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $recipe = RecipeRequest::with(['steps', 'ingredients', 'tags'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
        return response()->json($recipe);
    }

    /**
     * @throws \Throwable
     */
    public function store(RecipeRequestCreationRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $data = $request->validated();
        $data['image'] = $request->file('image')
            ->storePublicly(Recipe::IMAGE_FOLDER);
        $data['user_id'] = $request->user()->id;
        $recipeRequest = RecipeRequest::create($data);

        if ($request->filled('steps')) {
            foreach ($request->input('steps') as $step) {
                RecipeRequestStep::create([
                    'recipe_request_id' => $recipeRequest->id,
                    'name'              => $step['name'],
                    'description'       => $step['description'],
                    'duration'          => $step['duration'],
                ]);
            }
        }

        if ($request->filled('ingredients')) {
            foreach ($request->input('ingredients') as $ingredient) {
                RecipeRequestIngredient::create([
                    'recipe_request_id' => $recipeRequest->id,
                    'name'              => $ingredient['name'],
                    'quantity'          => $ingredient['quantity'],
                    'measure'           => $ingredient['measure'],
                ]);
            }
        }

        if ($request->filled('tags')) {
            $tagsData = $request->input('tags');
            $tags = array_map(fn($tag) => [
                'name'              => $tag,
                'recipe_request_id' => $recipeRequest->id,
            ], $tagsData);
            RecipeRequestTag::insert($tags);
        }

        DB::commit();
        $recipeRequest->load(['steps', 'ingredients', 'tags']);
        return response()->json($recipeRequest);
    }
}
