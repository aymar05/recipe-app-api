<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RecipeStep
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereUpdatedAt($value)
 * @property int $duration
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereDuration($value)
 * @property string $name
 * @property int $recipe_id
 * @property-read \App\Models\Recipe $recipe
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeStep whereRecipeId($value)
 * @mixin \Eloquent
 */
class RecipeStep extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'recipe_id'
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
