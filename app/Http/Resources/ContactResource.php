<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phone'    => Setting::localized($this->resource['phone']    ?? null),
            'whatsapp' => Setting::localized($this->resource['whatsapp'] ?? null),
            'email'    => Setting::localized($this->resource['email']    ?? null),
            'maps_url' => Setting::localized($this->resource['maps_url'] ?? null),
            'address'  => Setting::localized($this->resource['address']  ?? null),
            'hours'    => Setting::localized($this->resource['hours']    ?? null),
        ];
    }
}
