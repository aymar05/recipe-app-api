<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequestFilterRequest;
use App\Models\RecipeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RecipeRequestController extends Controller
{
    public function index(RecipeRequestFilterRequest $request): JsonResponse
    {
        return response()->json(
            QueryBuilder::for(RecipeRequest::class)
                ->with(['steps', 'recipe', 'user'])
                ->allowedFilters(['name', AllowedFilter::exact('status')])
                ->paginate(
                    perPage: $request->input('per_page', 1),
                    page: $request->input('page', 10)
                )
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(RecipeRequest::with(['steps', 'recipe', 'user'])->findOrFail($id));
    }

    public function reject(int $id): Response
    {
        $recipe = RecipeRequest::where('status', RequestStatus::Pending)
            ->findOrFail($id);
        $recipe->status = RequestStatus::Rejected;
        $recipe->save();
        return response()->noContent();
    }

    public function approve(int $id): Response
    {
        $recipe = RecipeRequest::where('status', RequestStatus::Pending)
            ->findOrFail($id);
        $recipe->status = RequestStatus::Approved;
        $recipe->save();
        return response()->noContent();
    }

    public function destroy(int $id): Response
    {
        $recipe = RecipeRequest::findOrFail($id);
        $recipe->delete();
        return response()->noContent();
    }
}
