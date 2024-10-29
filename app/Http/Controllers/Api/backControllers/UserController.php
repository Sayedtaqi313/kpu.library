<?php

namespace App\Http\Controllers\Api\backControllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StudentProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Student;
use Hash;

class UserController extends Controller
{

    public function userCreate(RegisterRequest $request)
    {
        $user = null;
        $faculty = Faculty::find($request->fac_id);
        $department = Department::find($request->dep_id);
        if (!$faculty) {
            return response()->json(['message' => "Faculty not found"], Response::HTTP_NOT_FOUND);
        }
        if (!$department) {
            return response()->json(['message' => "Department not found"], Response::HTTP_NOT_FOUND);
        }

        if (strtolower($request->type) == "teacher") {
            $teacher = Teacher::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $teacher->image()->create([
                    'image' => $path
                ]);
            }
            $user = $teacher->user()->create([
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "status" => $request->status,
                "type" => $request->type,
            ]);
        } else if (strtolower($request->type) == "student") {
            $student = Student::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $student->image()->create([
                    'image' => $path
                ]);

                $user = $student->user()->create([
                    "email" => $request->email,
                    "password" => Hash::make($request->password),
                    "status" => $request->status,
                    "type" => $request->type,
                ]);
            } else {
                return response()->json(['message' => 'Type is incorrect'], 400);
            }

            return response()->json(['user' => UserResource::make($user)]);
        }

    }

    public function userUpdate(UpdateUserRequest $request, User $user)
    {
        $faculty = Faculty::find($request->fac_id)->first();
        $department = Department::find($request->dep_id)->first();
        if (!$faculty) {
            return response()->json(['message' => 'Faculty not found'], Response::HTTP_NOT_FOUND);
        }
        if (!$department) {
            return response()->json(['message' => 'Department not found'], Response::HTTP_NOT_FOUND);
        }

        if ($request->type == "student" && $user->type == "student") {
            if ($request->hasFile('image')) {
                $oldPath = $user->userable->image->image;
                $arr = explode("/", $oldPath);
                array_shift($arr);
                $orgPath = implode("/", $arr);
                if (Storage::disk('public')->exists($orgPath)) {
                    Storage::disk('public')->delete($orgPath);
                }
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $user->userable->image->image = $path;
                $user->userable->image->save();
            }

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
            return response()->json(['message' => 'User updated successfully']);

        } else if ($request->type == "teacher" && $user->type == "student") {
            if ($request->hasFile('image')) {
                $oldPath = $user->userable->image->image;
                $arr = explode("/", $oldPath);
                array_shift($arr);
                $orgPath = implode("/", $arr);
                if (Storage::disk('public')->exists($orgPath)) {
                    Storage::disk('public')->delete($orgPath);
                }
            }
            $user->userable->image()->delete();
            $user->userable()->delete();
            $user->email = $request->email;
            $user->password = $request->password;
            $user->userable_id = $user->id;
            $user->userable_type = "App\Models\Teacher";
            $user->type = $request->type;
            $user->status = $request->status;
            $user->save();

            $teacher = Teacher::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "phone" => $request->phone,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $teacher->image()->create([
                    'image' => $path
                ]);
            }
            return response()->json(['message' => 'User updated successfully']);
        } else if ($request->type == "teacher" && $user->type == "teacher") {

            if ($request->hasFile('image')) {
                $oldPath = $user->userable->image->image;
                $arr = explode("/", $oldPath);
                array_shift($arr);
                $orgPath = implode("/", $arr);
                if (Storage::disk('public')->exists($orgPath)) {
                    Storage::disk('public')->delete($orgPath);
                }
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $user->userable->image->image = $path;
                $user->userable->image->save();
            }

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
            return response()->json(['message' => 'User updated successfully']);

        } else if ($request->type == "student" && $user->type == "teacher") {
            if ($request->hasFile('image')) {
                $oldPath = $user->userable->image->image;
                $arr = explode("/", $oldPath);
                array_shift($arr);
                $orgPath = implode("/", $arr);
                if (Storage::disk('public')->exists($orgPath)) {
                    Storage::disk('public')->delete($orgPath);
                }
            }

            $user->userable->image()->delete();
            $user->userable()->delete();
            $user->email = $request->email;
            $user->password = $request->password;
            $user->userable_id = $user->id;
            $user->userable_type = "App\Models\Student";
            $user->type = $request->type;
            $user->status = $request->status;
            $user->save();

            $student = Student::create([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "nin" => $request->nin,
                "nic" => $request->nic,
                "current_residence" => $request->current_residence,
                "original_residence" => $request->original_residence,
                "phone" => $request->phone,
                "fac_id" => $request->fac_id,
                "dep_id" => $request->dep_id,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/users', 'public');
                $path = "storage/" . $path;
                $student->image()->create([
                    'image' => $path
                ]);
            }

            return response()->json(['message' => 'User updated successfully']);
        }


    }

    public function destroy(User $user)
    {
        
            $oldPath = $user->userable->image->image;
            $arr = explode("/", $oldPath);
            array_shift($arr);
            $orgPath = implode("/", $arr);
            if (Storage::disk('public')->exists($orgPath)) {
                Storage::disk('public')->delete($orgPath);
            }
        $user->userable->image()->delete();
        $user->userable()->delete();
        $user->delete();
        return response()->json(['message' => "User deleted successfully"], Response::HTTP_NO_CONTENT);
    }
    public function getUnativatedUsers(Request $request)
    {
        $request->merge(['getUnativaedUsers' => "yes"]);

        $unactivatedUsers = User::where('status', 'inactive')->get();
        return UserResource::collection($unactivatedUsers);
    }

    public function getUnactivatedUserDetial(Request $request, string $id)
    {

        $request->merge(['getUnactivated_user_detail' => 'yes']);
        $unactivatedUser = User::where('id', '=', $id)->where('status', '=', 'inactive')->first();
        if ($unactivatedUser) {
            return UserResource::make($unactivatedUser);
        }

        return response()->json(['message', 'No user found'], Response::HTTP_NOT_FOUND);
    }

    public function ativatedUserById(Request $request, string $id)
    {
        $request->merge(['getUnactivated_user_detail' => 'yes']);
        $unactivatedUser = User::where('id', '=', $id)->where('status', '=', 'inactive')->first();
        if ($unactivatedUser) {
            $unactivatedUser->status = "active";
            $unactivatedUser->save();
            return UserResource::make($unactivatedUser);
        }
        return response()->json(['message', 'User not found'], Response::HTTP_NOT_FOUND);
    }

    public function getActivatedUsers(Request $request)
    {
        $request->merge(['getUnactivated_user_detail' => 'yes']);
        $users = User::where('status', '=', 'active')->get();
        if ($users) {
            return UserResource::collection($users);
        } else {
            return response()->json(['message' => 'No users activated yet']);
        }
    }
}