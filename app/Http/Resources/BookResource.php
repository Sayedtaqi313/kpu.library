<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StockResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //this respone if for the detail of the book;
        if($request->has('detial')) {
            return [
                'id' => $this->id,
                'image_url' => asset($this->image->image),
                'title' => $this->title,
                'author' => $this->author,
                'publisher' => $this->publisher,
                'description' => $this->description,
                'publicationYear' => $this->publicationYear,
                'lang' => $this->lang,
                'edition' => $this->edition,
                'translator' => $this->translator,
                'isbn' => $this->isbn,
                'format' => $this->format,
                'barrow' => $this->barrow,
                'category' => (CategoryResource::make($this->category))
            ];
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'image_url' => asset($this->image->image),
            'publicationYear' => $this->publicationYear,
            'lang' => $this->lang,
            'edition' => $this->edition,
            'translator' => $this->translator,
        ];
    }

    
}
