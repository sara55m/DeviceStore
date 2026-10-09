<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

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
