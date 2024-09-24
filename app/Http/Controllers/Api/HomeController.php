<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\BookResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Hash;

class HomeController extends Controller
{
 
    public function register(RegisterRequest $request)
    {
        $user = null;
        if (strtolower($request->type) == "teacher") {
            $teacher = Teacher::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => "kabul",
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

          $user =  $teacher->user()->create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "status" => "inactive",
                "type" => $request->type,
            ]);
        } else if (strtolower($request->type) == "student") {
            $student = Student::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => "kabul",
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);
 
           $user = $student->user()->create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "status" => "inactive",
                "type" => $request->type,
            ]);
        } else {
            return response()->json(['message' => 'Type is incorrect'], 400);
        }

        $token = $user->createToken($user->email . '123')->plainTextToken;
        return response()->json(['token' => $token, 'user' => UserResource::make($user)]);
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user) {
            if(Hash::check($request->password,$user->password)){
                $token = $user->createToken($user->email . '123')->plainTextToken;
                return response()->json(['token' => $token, 'user' => UserResource::make($user)]);
            }else {
                return response()->json(['message'=>'Credential is incorrect']);
            }
        }else {
            return response()->json(['message'=>'Credential is incorrect']);
        }
    }

    public function logout()
    {
       return auth()->user()->tokens()->delete();
       return response()->noContent();
    }

    public function home(Request $request) {
        $request->merge(['category_with_books' => 'yes']);
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function booksByCategoryId(Request $request , string $id) {
        $request->merge(['category_with_books'=>'yes']);
        $category = Category::find($id);

        if($category) {
            return CategoryResource::make($category);
        }

        return response()->json(['message' => "item not found"],Response::HTTP_NOT_FOUND);
    } 

    public function BookDetialById(Request $request , string $id) {
        $request->merge(['detial' => "yes"]);
        $book = Book::find($id);
        if($book) {
            return BookResource::make($book);
        }

        return response()->json(['message' => "item not found"],Response::HTTP_NOT_FOUND);
    }
}