<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Http\Resources\FacultyResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{

    public function index()
    {
        $faculties = Faculty::all();
        if ($faculties) {
            return FacultyResource::collection($faculties);
        } else {
            return response()->json(['message' => 'فاکولته وجود ندارد', 'data' => []], Response::HTTP_OK);
        }
    }


    public function store(FacultyRequest $request)
    {
        $faculty = Faculty::create([
            "name" => $request->name
        ]);

        return FacultyResource::make($faculty);
    }



    public function update(UpdateFacultyRequest $request, string $id)
    {
        $faculty = Faculty::find($id);
        if ($faculty) {
            $faculty->update([
                "name" => $request->name
            ]);
            return FacultyResource::make($faculty);
        } else {
            return response()->json(['message' => "فاکولته پیدا نشد"], Response::HTTP_NOT_FOUND);
        }
    }


    public function destroy(string $id)
    {
        $faculty = Faculty::find($id);
        if ($faculty) {
            $faculty->delete();
            return response()->json(['message' => "فاکولته موفقانه پاک شد"], Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['message' => "فاکولته پیدا نشد"], Response::HTTP_NOT_FOUND);
        }
    }
}
