<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductImageResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $images=$this->images;

        return array_merge(parent::toArray($request), [
            'name'        => $this->name,
            'image'       => ($images->firstWhere('is_primary', true) ?? $images->first())?->url,
            'description' => $this->description ?: null,
            'specs'       => collect($this->specs ?? [])
                                ->map(fn ($s) => ['label' => $s['key'], 'value' => $s['value']])
                                ->all(),
            'images'      => ProductImageResource::collection($images),
        ]);
    }
}
