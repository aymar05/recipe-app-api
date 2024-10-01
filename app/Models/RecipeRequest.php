<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\RecipeRequest
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property int $preparation_time
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property RequestStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \App\Models\Recipe|null $recipe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeRequestStep> $recipeRequestSteps
 * @property-read int|null $recipe_request_steps_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest wherePreparationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereUserId($value)
 * @property int|null $recipe_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeRequestStep> $steps
 * @property-read int|null $steps_count
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereRecipeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeRequest whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeRequestIngredient> $ingredients
 * @property-read int|null $ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeRequestTag> $tags
 * @property-read int|null $tags_count
 * @mixin \Eloquent
 */
class RecipeRequest extends Model
{
    protected $fillable = [
        'title',
        'image',
        'preparation_time',
        'user_id',
        'status',
        'recipe_id',
    ];

    protected $casts = [
        'status' => RequestStatus::class,
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeRequestStep::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeRequestIngredient::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): HasOne
    {
        return $this->hasOne(Recipe::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(RecipeRequestTag::class);
    }
}
