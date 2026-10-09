<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'sort_order', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    //delete the image file when the model is deleted
    protected static function booted(): void
    {
        static::deleting(fn (ProductImage $image) => Storage::disk('public')->delete($image->path));
    }

    // asset() uses the current host, so images work on 127.0.0.1 and localhost alike.
    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/'.$this->path));
    }
}
