<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $faculties = Department::all();
        if($faculties) {
            return DepartmentResource::collection($faculties);
        }else {
            return response()->json(['message' => 'no deparment found' ,'data' => []],Response::HTTP_OK );
        }
    }

  
    public function store(DepartmentRequest $request)
    {  
        $Department = Department::create([
            "fac_id" => $request->fac_id,
            "name" => $request->name
           
        ]);

        return DepartmentResource::make($Department);
    }

  
    public function show(string $id)
    {
        
    }

   
    public function update(DepartmentRequest $request, string $id)
    {
        $department = Department::find($id);
        if($department) {
            $department->update([
                "name" => $request->name,
                "fac_id" => $request->fac_id
            
            ]);
            return DepartmentResource::make($department);
        }else {
            return response()->json(['message' => "Department not found"],Response::HTTP_NOT_FOUND);
        }
      
    }

  
    public function destroy(string $id)
    {  
        $department = Department::find($id);
        if($department) {
            $department->delete();
            return response()->json(['message' => "Department deleted successfully"],Response::HTTP_NO_CONTENT);
        }else {
            return response()->json(['message' => "Dpartment not found"],Response::HTTP_NOT_FOUND);
        }
     
    }
}
