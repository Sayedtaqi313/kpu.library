<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Section;
use App\Models\Department;
use App\Models\Category;
use App\Models\Pdf;




use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::all();
        if ($books) {
            return BookResource::collection($books);
        } else {
            return response()->json(['message' => 'کتاب وجود ندارد', 'data' => []], Response::HTTP_OK);
        }
    }


    public function store(StoreBookRequest $request)
    {

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->publicationYear = $request->publicationYear;
        $book->lang = $request->lang;
        $book->edition = $request->edition;
        $book->translator = $request->translator;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->code = $request->code;
        $book->cat_id = $request->cat_id;
        $book->dep_id = $request->dep_id;
        $book->sec_id = $request->sec_id;
        $book->format = $request->format;
        $book->borrow = $request->borrow;

        $format = $request->format;
        if ($format == "hard") {
            $book->save();
            $path = $request->file('image')->store('images/books', 'public');
            $path = "storage/" . $path;
            $book->image()->create([
                'image' => $path
            ]);
        } else if ($format == "pdf") {
            if ($request->hasFile('image') && $request->hasFile('pdf')) {
                $book->save();
                $path = $request->file('image')->store('images/books', 'public');
                $path = "storage/" . $path;
                $book->image()->create([
                    'image' => $path
                ]);

                $pdfPath = $request->file('pdf')->store('pdfs', 'public');
                $pdfPath = "storage/" . $pdfPath;
                Pdf::create([
                    'book_id' => $book->id,
                    'path' => $pdfPath,
                ]);
            } else {
                return response()->json(['message' => 'عکس و فایل هردو ضروری می باشد']);
            }
        } else if ($format == 'both') {
            if ($request->hasFile('image') && $request->hasFile('pdf')) {
                $book->save();
                $path = $request->file('image')->store('images/books', 'public');
                $path = "storage/" . $path;
                $book->image()->create([
                    'image' => $path
                ]);
                $pdfPath = $request->file('pdf')->store('pdfs', 'public');
                $pdfPath = "storage/" . $pdfPath;
                Pdf::create([
                    'book_id' => $book->id,
                    'path' => $pdfPath,
                ]);
            } else {
                return response()->json(['message' => 'عکس و فایل هردو ضروری می باشد']);
            }
        }


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


    public function update(UpdateBookRequest $request, string $id)
    {

        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => "کتاب وجود ندارد"], Response::HTTP_NOT_FOUND);
        }


        $oldFormat = $book->format;
        $newFormat = $request->format;

        // Update book's primary fields
        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'publicationYear' => $request->publicationYear,
            'lang' => $request->lang,
            'edition' => $request->edition,
            'translator' => $request->translator,
            'isbn' => $request->isbn,
            'code' => $request->code,
            'description' => $request->description,
            'cat_id' => $request->cat_id,
            'dep_id' => $request->dep_id,
            'sec_id' => $request->sec_id,
            'format' => $newFormat,
            'barrow' => $request->barrow,
        ]);

        // Scenario 1: Old format is the same as the new format
        if ($oldFormat === $newFormat) {
            if ($newFormat === 'hard' || $newFormat === 'both' || $newFormat === 'pdf') {
                // Update image if it has changed
                if ($request->hasFile('image')) {
                    if ($book->image) {
                        Storage::disk('public')->delete(str_replace("storage/", "", $book->image->image));
                        $book->image()->delete();
                    }
                    $imagePath = $request->file('image')->store('images/books', 'public');
                    $book->image()->create(['image' => "storage/" . $imagePath]);
                }
            }

            if ($newFormat === 'pdf' || $newFormat === 'both') {
                // Update PDF if it has changed
                if ($request->hasFile('pdf')) {
                    $existingPdf = Pdf::where('book_id', $book->id)->first();
                    if ($existingPdf) {
                        Storage::disk('public')->delete(str_replace("storage/", "", $existingPdf->path));
                        $existingPdf->delete();
                    }
                    $pdfPath = $request->file('pdf')->store('pdfs', 'public');
                    Pdf::create([
                        'book_id' => $book->id,
                        'path' => "storage/" . $pdfPath,
                    ]);
                }
            }
        }

        // Scenario 2: Old format is different from the new format
        else {
            // Remove old format files
            if ($oldFormat === 'hard' || $oldFormat === 'both' || $oldFormat === 'pdf') {
                if ($book->image) {
                    Storage::disk('public')->delete(str_replace("storage/", "", $book->image->image));
                    $book->image()->delete();
                }
            }

            if ($oldFormat === 'pdf' || $oldFormat === 'both') {
                $existingPdf = Pdf::where('book_id', $book->id)->first();
                if ($existingPdf) {
                    Storage::disk('public')->delete(str_replace("storage/", "", $existingPdf->path));
                    $existingPdf->delete();
                }
            }

            // Add new format files
            if ($newFormat === 'hard' || $newFormat === 'both' || $newFormat === 'pdf') {
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images/books', 'public');
                    $book->image()->create(['image' => "storage/" . $imagePath]);
                }
            }
            if ($newFormat === 'pdf' || $newFormat === 'both') {
                if ($request->hasFile('pdf')) {
                    $pdfPath = $request->file('pdf')->store('pdfs', 'public');
                    Pdf::create([
                        'book_id' => $book->id,
                        'path' => "storage/" . $pdfPath,
                    ]);
                }
            }
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
        if ($book) {
            $book->delete();
            return response()->json(['message' => "کتاب موفقانه پاک شد"], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['message' => "کتاب وجود ندراد"], Response::HTTP_NOT_FOUND);
        }
    }
}
