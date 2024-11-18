<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StudentProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Student;


class UserController extends Controller
{

    public function userCreate(RegisterRequest $request)
    {
        $user = null;
        $faculty = Faculty::find($request->fac_id);
        $department = Department::find($request->dep_id);
        if (!$faculty) {
            return response()->json(['message' => "فاکولته وجودندارد"], Response::HTTP_NOT_FOUND);
        }
        if (!$department) {
            return response()->json(['message' => "دیپارتمنت وجود ندراد"], Response::HTTP_NOT_FOUND);
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
                return response()->json(['message' => 'تایپ نادرست می باشد'], 400);
            }

            return response()->json(['user' => UserResource::make($user)]);
        }
    }

    public function userUpdate(UpdateUserRequest $request, User $user)
    {
        $faculty = Faculty::find($request->fac_id)->first();
        $department = Department::find($request->dep_id)->first();
        if (!$faculty) {
            return response()->json(['message' => 'فاکولته وجود ندارد'], Response::HTTP_NOT_FOUND);
        }
        if (!$department) {
            return response()->json(['message' => 'دیپارتمنت وجود ندارد'], Response::HTTP_NOT_FOUND);
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
            return response()->json(['message' => 'کاربر موفقانه بروز رسانی شد']);
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
            return response()->json(['message' => 'کاربر موفقانه بروز رسانی شد']);
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
            return response()->json(['message' => 'کربر موفقانه بروز رسانی شد']);
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

            return response()->json(['message' => 'کاربر موفقانه بروزرسانی شد']);
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
        return response()->json(['message' => "کابر موففانه پاک شد"], Response::HTTP_NO_CONTENT);
    }


    public function getInactivatedStudents(Request $request)
    {
        $request->merge(['getInactivatedUsers' => "yes"]);

        $inactivatedUsers = User::where('status', 'inactive')->where('type', 'student')->get();
        return UserResource::collection($inactivatedUsers);
    }

    public function getInactivatedTeachers(Request $request)
    {
        $request->merge(['getInactivatedUsers' => "yes"]);

        $inactivatedUsers = User::where('status', 'inactive')->where('type', 'teacher')->get();
        return UserResource::collection($inactivatedUsers);
    }

    public function getInactivatedUserDetail(Request $request, string $id)
    {

        $request->merge(['getInactivated_user_detail' => 'yes']);
        $inactivatedUser = User::where('id', '=', $id)->where('status', '=', 'inactive')->first();
        if ($inactivatedUser) {
            return UserResource::make($inactivatedUser);
        }

        return response()->json(['message' => 'کاربر پیدا نشد'], Response::HTTP_NOT_FOUND);
    }

    public function activateUserById(Request $request, string $id)
    {
        $request->merge(['getInactivated_user_detail' => 'yes']);
        $inactivatedUser = User::where('id', '=', $id)->where('status', '=', 'inactive')->first();
        if ($inactivatedUser) {
            $inactivatedUser->status = "active";
            $inactivatedUser->save();
            return response()->json(['message' => 'کاربر موفقانه فعال شد', 'data' => UserResource::make($inactivatedUser)]);
        }
        return response()->json(['message' => 'کاربر پیدا نشد'], Response::HTTP_NOT_FOUND);
    }

    public function getActivatedStudents(Request $request)
    {
        $request->merge(['getInactivated_user_detail' => 'yes']);
        $users = User::where('status', 'active')
            ->where('type', 'student')->get();
        if ($users) {
            return UserResource::collection($users);
        } else {
            return response()->json(['message' => 'کاربر فعال وجود ندارد']);
        }
    }
    public function getActivatedTeachers(Request $request)
    {
        $request->merge(['getInactivated_user_detail' => 'yes']);
        $users = User::where('status', 'active')
            ->where('type', 'teacher')->get();
        if ($users) {
            return UserResource::collection($users);
        } else {
            return response()->json(['message' => 'کاربر فعال وجود ندارد']);
        }
    }

    public function getActivatedUserById(Request $request, User $user)
    {
        $request->merge(['getInactivated_user_detail' => 'yes']);
        return UserResource::make($user);
    }
}
