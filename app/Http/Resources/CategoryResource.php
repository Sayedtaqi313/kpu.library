<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //get categories with all it's books;
        if($request->has('category_with_books')){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'books' => BookResource::collection($this->books),
            ];
        };
        

        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
