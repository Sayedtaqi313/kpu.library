<?php

namespace App\Http\Controllers\Api\FrontControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
    public function showProfile(Request $request,)
    {
        $user = auth()->user();
        if ($user->type == "student") {
            return StudentProfileResource::make($user);
        } else if ($user->type == "teacher") {
        } else {
            return response()->json(['message' => 'تابپ کاربر نامعتبر می باشد']);
        }
    }

    public function updateProfile(StudentProfileRequest $request)
    {

        $user = auth()->user();
        if ($user->type == "student") {
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

            $user->email = $request->email;
            $user->password = $request->password !== null ? Hash::make($request->password) : $user->password;
            $user->save();
            $user->userable->phone = $request->phone;
            $user->userable->save();
            return response()->json(['message' => 'پروفایل شما موفقانه بروزرسانی شد']);
        } else if ($user->type == "teacher") {
        }
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $fine = Fine::where('user_id', $user->id)->where('paid', 'no')->first();
        if ($fine) {
            return response()->json(['message' => 'شما اول بابد جریمه خود را پرداخت کنید']);
        }
        $user->tokens()->delete();
        $user->userable->image()->delete();
        $user->userable()->delete();
        $user->delete();
        return response()->noContent();
    }
}
