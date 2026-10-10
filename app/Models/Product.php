<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasTranslations, SoftDeletes;

    public array $translatable = ['name', 'description'];

    protected $fillable = ['category_id', 'name', 'description', 'slug', 'sku', 'price', 'specs', 'is_active', 'is_featured'];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        //remove whitespaces,backslashes and signs like %
        $like = '%'.addcslashes(trim($term), '\\%_').'%';

        return $query->where(function ($q) use ($like) {
            $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) COLLATE utf8mb4_unicode_ci LIKE ?", [$like])
            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')) COLLATE utf8mb4_unicode_ci LIKE ?", [$like])
            ->orWhere('sku', 'like', $like);
        });
    }

    protected static function booted(): void
    {
        // Slug is generated once from the English name and never changes (stable URLs).
        static::creating(function (Product $product) {
            $base = Str::slug($product->getTranslation('name', 'en')) ?: 'product';
            $slug = $base;
            $i = 2;
            while (static::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $product->slug = $slug;
        });

        // Permanent delete: remove image rows through the model so files are deleted too.
        static::forceDeleting(fn (Product $product) => $product->images->each->delete());
    }

}
