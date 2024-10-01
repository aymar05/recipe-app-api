<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(int $recipeId, CommentRequest $request): JsonResponse
    {
        $recipeExists = Recipe::where('id', $recipeId)
            ->exists();
        abort_if(!$recipeExists, 404);

        $comment = Comment::create([
            'recipe_id' => $recipeId,
            'user_id'   => $request->user()->id,
            'text'      => $request->input('text')
        ]);
        $comment->refresh();
        return response()->json($comment);
    }

    public function update(int $id, CommentRequest $request): JsonResponse
    {
        $comment = Comment::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $comment->update(['text' => $request->input('text')]);
        return response()->json($comment);
    }

    public function destroy(int $id, Request $request): Response
    {
        $comment = Comment::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $comment->delete();
        return response()->noContent();
    }
}
