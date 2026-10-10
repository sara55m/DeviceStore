<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'name'        => $this->name,
            'sku'         => $this->sku,
            'price'       => $this->price === null ? null : $this->price / 100,
            'currency'    => 'EGP',
            'is_featured' => $this->is_featured,
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'image'       => $this->whenLoaded('primaryImage', fn () => $this->primaryImage?->url),
        ];
    }
}
