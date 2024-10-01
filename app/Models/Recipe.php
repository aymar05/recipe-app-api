<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Recipe
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property float $evaluation
 * @property int $time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ingredient> $ingredients
 * @property-read int|null $ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecipeStep> $steps
 * @property-read int|null $steps_count
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereEvaluation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereUpdatedAt($value)
 * @property-read string $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property int|null $recipe_request_id
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereRecipeRequestId($value)
 * @mixin \Eloquent
 */
class Recipe extends Model
{
    protected $fillable = [
        'title',
        'image',
        'evaluation',
        'time',
        'recipe_request_id',
    ];

    protected $appends = [
        'image_url',
    ];

    const IMAGE_FOLDER = 'public/recipes';

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getImageUrlAttribute(): string
    {
        return 'storage' . substr($this->image, 7);
    }
}
