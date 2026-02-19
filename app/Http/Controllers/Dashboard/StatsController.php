<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeRequest;
use App\Models\Tag;
use App\Models\User;
use App\Enums\RequestStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        //Compteurs
        $totalRecipes         = Recipe::count();
        $totalUsers           = User::count();
        $totalActiveRequests  = RecipeRequest::where('status', RequestStatus::Approved)->count();
        $totalTags            = Tag::count();

        // Recettes ajoutées par mois (12 derniers mois)
        $recipesPerMonth = Recipe::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as sort_key"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key')
            ->get()
            ->map(fn ($row) => [
                'month' => $row->month,
                'count' => $row->count,
            ]);

        // Top 5 tags les plus utilisés
        $topTags = Tag::withCount('recipes')
            ->orderByDesc('recipes_count')
            ->limit(5)
            ->get()
            ->map(fn ($tag) => [
                'name'          => $tag->name,
                'recipes_count' => $tag->recipes_count,
            ]);

        //  5 derniers utilisateurs inscrits
        $latestUsers = User::select('id', 'name', 'email', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'total_recipes'         => $totalRecipes,
            'total_users'           => $totalUsers,
            'total_active_requests' => $totalActiveRequests,
            'total_tags'            => $totalTags,
            'recipes_per_month'     => $recipesPerMonth,
            'top_tags'              => $topTags,
            'latest_users'          => $latestUsers,
        ]);
    }
}
