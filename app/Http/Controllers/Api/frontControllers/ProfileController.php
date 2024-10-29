<?php

namespace App\Http\Controllers\Api\frontControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentProfileRequest;
use App\Http\Resources\StudentProfileResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Fine;

class ProfileController extends Controller
{
   public function showProfile(Request $request,) {
        $user =  auth()->user();
        if($user->type == "student") {
            return StudentProfileResource::make($user);
        }else if($user->type == "teacher"){

        }else {
            return response()->json(['message' => 'User type is unkonwn']);
        }
        
   }

   public function updateProfile(StudentProfileRequest $request) {
       $faculty = Faculty::find($request->fac_id)->first();
       $department = Department::find($request->dep_id)->first();
       if(!$faculty){
           return response()->json(['message' => 'Faculty not found'],Response::HTTP_NOT_FOUND);
       }
       if(!$department) {
           return response()->json(['message' => 'Department not found'],Response::HTTP_NOT_FOUND);
       }
       $user = auth()->user();
       if($user->type == "student") {
            if($request->hasFile('image')){
                $oldPath = $user->userable->image->image;
                $arr = explode("/",$oldPath);
                array_shift($arr);
                $orgPath = implode("/",$arr);
                if(Storage::disk('public')->exists($orgPath)){
                    Storage::disk('public')->delete($orgPath);
                }

                $path = $request->file('image')->store('images/users','public');
                $path = "storage/" . $path;
                $user->userable->image->image = $path;
                $user->userable->image->save();
                
            }

            $user->email = $request->email;
            $user->save();
           
            $user->userable->firstName = $request->firstName;
            $user->userable->lastName = $request->lastName;
            $user->userable->nin = $request->nin;
            $user->userable->nic = $request->nic;
            $user->userable->current_residence = $request->current_residence;
            $user->userable->original_residence = $request->original_residence;
            $user->userable->phone = $request->phone;
            $user->userable->fac_id = $request->fac_id;
            $user->userable->dep_id = $request->dep_id;
            $user->userable->save();
            return response()->json(['message'=>'Profile updated successfully']);
       }else if($user->type == "teacher") {

       }
   } 

   public function deleteAcount() {
    $user = auth()->user();
    $fine = Fine::where('user_id',$user->id)->where('paid','no')->first();
    if($fine) {
        return response()->json(['message'=>'First you have to pay your fine']);
    }
       $user->tokens()->delete();
       $user->userable->image()->delete();
       $user->userable()->delete();
       $user->delete();
       return response()->noContent();
   }
}
