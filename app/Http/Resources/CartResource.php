<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->book->title,
            'image_url' => $this->book->image->image,
            'book_type' => $this->book->barrow == "yes" ? "barrowable" : "reservable"
        ];
    }
}
