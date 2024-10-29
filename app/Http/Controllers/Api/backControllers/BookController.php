<?php

namespace App\Http\Controllers\Api\backControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Section;
use App\Models\Department;
use App\Models\Category;



use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    
    public function index()
    {
        $books = Book::all();
        if($books) {
            return BookResource::collection($books);
        }else {
            return response()->json(['message' => 'no book found' ,'data' => []],Response::HTTP_OK );
        }
    }

   
    public function store(StoreBookRequest $request)
    {
        $seciton = Section::find($request->sec_id);
        $category = Category::find($request->cat_id);
        $department = Department::find($request->dep_id);
        if(!$seciton) {
            return response()->json(['message' => "Section not found"],Response::HTTP_NOT_FOUND);
        }else if(!$category) {
            return response()->json(['message' => "Category not found"],Response::HTTP_NOT_FOUND);
        }else if(!$department) {
            return response()->json(['message' => "Department not found"],Response::HTTP_NOT_FOUND);
        }

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
            "code" => $request->code,
            'cat_id' => $request->cat_id,
            'dep_id' => $request->dep_id,
            'sec_id' => $request->sec_id,
            'format' => $request->format,
            'barrow' => $request->barrow
        ]);
        $path = $request->file('image')->store('images/books','public');
        $path = "storage/" . $path; 
        $book->image()->create([
            'image' => $path
        ]);

        $section = Section::find($request->sec_id);
        $section->shelf = $request->shelf;
        $section->save();

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

 
    public function update(UpdateBookRequest $request, string $id)
    {
        $seciton = Section::find($request->sec_id);
        $category = Category::find($request->cat_id);
        $department = Department::find($request->dep_id);
        if(!$seciton) {
            return response()->json(['message' => "Section not found"],Response::HTTP_NOT_FOUND);
        }else if(!$category) {
            return response()->json(['message' => "Category not found"],Response::HTTP_NOT_FOUND);
        }else if(!$department) {
            return response()->json(['message' => "Department not found"],Response::HTTP_NOT_FOUND);
        }
            
        $book = Book::find($id);
        if(!$book){
            return response()->json(['message' => "Book not found"],Response::HTTP_NOT_FOUND);
        }
         $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publicationYear' => $request->publicationYear,
            'lang' => $request->lang,
            'edition' => $request->edition,
            'translator' => $request->translator,
            'isbn' => $request->isbn,
            "code" => $request->code,
            'description' => $request->description,
            'cat_id' => $request->cat_id,
            'dep_id' => $request->dep_id,
            'sec_id' => $request->sec_id,
            'format' => $request->format,
            'barrow' => $request->barrow
        ]);
        
        if($request->hasFile('image')){
            $orgPath = explode("/",$book->image->image);
            array_shift($orgPath);
            $orgPath = implode("/",$orgPath);
            if(Storage::disk('public')->exists($orgPath)){
                Storage::disk('public')->delete($orgPath);
            }
            $path = $request->file('image')->store('images/books','public');
            $path = $path = "storage/" . $path; 
            $book->image()->update([
                'image' => $path
            ]);
            }

            $section = Section::find($request->sec_id);
            $section->shelf = $request->shelf;
            $section->save();

            $book->stock()->update([
                'book_id' => $book->id,
                'total' => $request->total,
                'remain' => $request->total,
                'status' => "exist"
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
