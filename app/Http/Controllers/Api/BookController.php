<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;


use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    
    public function index()
    {
        $books = Book::paginate(10);
        if($books) {
            return BookResource::collection($books);
        }else {
            return response()->json(['message' => 'no book found' ,'data' => []],Response::HTTP_OK );
        }
    }

   
    public function store(StoreBookRequest $request)
    {
   
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publicationYear' => $request->publicationYear,
            'lang' => $request->lang,
            'edition' => $request->edition,
            'translator' => $request->translator,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'cat_id' => $request->cat_id,
            'dep_id' => $request->dep_id,
            'sec_id' => $request->sec_id,
            'format' => $request->format,
            'barrow' => $request->barrow
        ]);
        $path = $request->file('image')->store('images/books','public');
        $book->image()->create([
            'image' => $path
        ]);

        $book->stock()->create([
            'book_id' => $book->id,
            'total' => $request->total,
            'remain' => $request->total,
            'status' => "exist",
        ]);
        return BookResource::make($book);
    }

  
    public function show(string $id)
    {
        //
    }

 
    public function update(UpdateBookRequest $request, Book $book)
    {
        
         $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publicationYear' => $request->publicationYear,
            'lang' => $request->lang,
            'edition' => $request->edition,
            'translator' => $request->translator,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'cat_id' => $request->cat_id,
            'dep_id' => $request->dep_id,
            'sec_id' => $request->sec_id,
            'format' => $request->format,
            'barrow' => $request->barrow
        ]);
        
        if($request->hasFile('image')){
            if(Storage::disk('public')->exists($book->image->image)){
                Storage::disk('public')->delete($book->image->image);
            }
            $path = $request->file('image')->store('images/books','public');
            $book->image()->update([
                'image' => $path
            ]);
            }

            $book->stock()->update([
                'book_id' => $book->id,
                'total' => $request->total,
                'remain' => $request->total,
                'status' => $request->status
            ]);
         
        
        return BookResource::make($book);
    
    }

  
    public function destroy(string $id)
    {
        $book = Book::find($id);;
        if($book) {
            $book->delete();
            return response()->json(['message' => "book deleted successfully"],Response::HTTP_NO_CONTENT);
        }else {
            return response()->json(['message' => "item not found"],Response::HTTP_NOT_FOUND);
        }
    }
}
