<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RecipeRequestStep
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RecipeRequest|null $recipeRequest
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereUpdatedAt($value)
 * @property int $recipe_request_id
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestStep whereRecipeRequestId($value)
 * @mixin \Eloquent
 */
class RecipeRequestStep extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'recipe_request_id'
    ];

    public function recipeRequest(): BelongsTo
    {
        return $this->belongsTo(RecipeRequest::class);
    }
}
