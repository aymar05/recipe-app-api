<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\RecipeRequestTag
 *
 * @property int $id
 * @property string $name
 * @property int $recipe_request_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeRequest> $recipes
 * @property-read int|null $recipes_count
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag whereRecipeRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequestTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecipeRequestTag extends Model
{
    protected $fillable = [
        'name',
        'recipe_request_id'
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(RecipeRequest::class);
    }
}
