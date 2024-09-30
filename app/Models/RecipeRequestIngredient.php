<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RecipeRequestIngredient
 *
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property string $measure
 * @property int $recipe_request_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RecipeRequest|null $recipe
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereRecipeRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestIngredient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecipeRequestIngredient extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'measure',
        'recipe_request_id',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(RecipeRequest::class);
    }
}
