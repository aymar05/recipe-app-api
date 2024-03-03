<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeRequestStep extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function recipeRequest(): BelongsTo
    {
        return $this->belongsTo(RecipeRequest::class);
    }
}
