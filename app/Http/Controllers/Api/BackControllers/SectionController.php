<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::all();
        if ($sections) {
            return SectionResource::collection($sections);
        } else {
            return response()->json(['message' => 'الماری وجود ندارد', 'data' => []], Response::HTTP_OK);
        }
    }


    public function store(SectionRequest $request)
    {
        $section = Section::create([
            "section" => $request->section,
        ]);

        return SectionResource::make($section);
    }


    public function show(string $id)
    {
        //
    }


    public function update(SectionRequest $request, Section $section)
    {
        $section->update([
            "section" => $request->section,
        ]);

        return SectionResource::make($section);
    }


    public function destroy(string $id)
    {
        $section = Section::find($id);
        if ($section) {
            $section->delete();
            return response()->json(['message', 'الماری موفقانه پاک شد'], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['message', 'الماری وجود ندارد'], Response::HTTP_NOT_FOUND);
        }
    }
}
