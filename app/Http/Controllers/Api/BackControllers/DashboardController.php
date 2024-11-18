<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use App\Models\Faculty;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $request->merge(['faculties_with_departments' => 'yes']);
        $faculties = Faculty::all();
        return FacultyResource::collection($faculties);
    }

    public function getFacultyWithDepartments(Request $request) {
        $request->merge(['faculties_with_departments' => 'yes']);
        $faculties = Faculty::all();
        return FacultyResource::collection($faculties);
    }


}