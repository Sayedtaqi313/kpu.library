<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StockResource;
use App\Models\Book;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //this response if for the detail of the book;
        if ($request->has('detail')) {
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
                'category' => (CategoryResource::make($this->category)),
                'faculty' => (FacultyResource::make($this->department->faculty)),
                'department' => (DepartmentResource::make($this->department)),
                'related_books_category' => Book::where('cat_id', $this->cat_id)->get(),
                'related_books_department' => Book::where('dep_id', $this->dep_id)->get()

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
            'cat_id' => $this->cat_id,
            'dep_id' => $this->dep_id,
            'isbn' => $this->isbn,
            'code' => $this->code,
            'description' => $this->description,
            'borrow' => $this->borrow == "yes" ? "borrowable" : "reservable",
            'category' => $this->category->name,
            'faculty' => $this->department->faculty->name,
            'department' => $this->department->name
        ];
    }
}
