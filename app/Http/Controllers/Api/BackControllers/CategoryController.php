<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }


    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            "name" => $request->name
        ]);

        return CategoryResource::make($category);
    }



    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update([
                "name" => $request->name
            ]);

            return CategoryResource::make($category);
        }

        return response()->json(['message' => "کتگوری وجود ندارد"], Response::HTTP_NOT_FOUND);
    }


    public function destroy(string $id)
    {
        $Category = Category::find($id);
        if ($Category) {
            $Category->delete();
            return response()->json(['message' => "کتگوری موفقانه پاک شد"], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['message' => "کتگوری وجود ندارد"], Response::HTTP_NOT_FOUND);
        }
    }
}
