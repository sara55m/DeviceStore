<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;

class AboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "title"=>Setting::localized($this->resource['title'] ?? null),
            "story"=>Setting::localized($this->resource['story'] ?? null),
            'mission' => Setting::localized($this->resource['mission'] ?? null),
            'vision'  => Setting::localized($this->resource['vision']  ?? null),

        ];
    }
}
