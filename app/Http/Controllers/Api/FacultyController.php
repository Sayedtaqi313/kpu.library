<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\Http\Resources\FacultyResource;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    
    public function index()
    {
        $faculties = Faculty::all();
        if($faculties) {
            return FacultyResource::collection($faculties);
        }else {
            return response()->json(['message' => 'no faculty found' ,'data' => []],Response::HTTP_OK );
        }
    }

  
    public function store(FacultyRequest $request)
    {
        $faculty = Faculty::create([
            "name" => $request->name
        ]);

        return FacultyResource::make($faculty);
    }

  
    public function show(string $id)
    {
        
    }

   
    public function update(FacultyRequest $request, Faculty $faculty)
    {
        $faculty->update([
            "name" => $request->name
        ]);

        return FacultyResource::make($faculty);
    }

  
    public function destroy(string $id)
    {  
        $faculty = Faculty::find($id);
        if($faculty) {
            $faculty->delete();
            return response()->json(['message' => "faculty deleted successfully"],Response::HTTP_NO_CONTENT);
        }else {
            return response()->json(['message' => "item not found"],Response::HTTP_NOT_FOUND);
        }
     
    }
}
