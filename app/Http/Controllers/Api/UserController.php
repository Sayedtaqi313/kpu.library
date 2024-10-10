<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUnativatedUsers(Request $request){
        $request->merge(['getUnativaedUsers'=>"yes"]);

        $unactivatedUsers = User::where('status','inactive')->get();
        return UserResource::collection($unactivatedUsers);
    }

    public function getUnactivatedUserDetial(Request $request, string $id) {
 
        $request->merge(['getUnactivated_user_detail'=>'yes']);
        $unactivatedUser = User::where('id','=',$id)->where('status','=','inactive')->first();
        if($unactivatedUser) {
            return UserResource::make($unactivatedUser);
        }

        return response()->json(['message','No user found'],Response::HTTP_NOT_FOUND);
    }

    public function ativatedUserById(Request $request,string $id) {
        $request->merge(['getUnactivated_user_detail'=>'yes']);
        $unactivatedUser = User::where('id','=',$id)->where('status','=','inactive')->first();
        if($unactivatedUser) {
            $unactivatedUser->status = "active";
            $unactivatedUser->save();
            return UserResource::make($unactivatedUser);
        }
        return response()->json(['message','User not found'],Response::HTTP_NOT_FOUND);
    }

    public function getActivatedUsers(Request $request) {
        $request->merge(['getUnactivated_user_detail' => 'yes']);
        $users = User::where('status','active')->get();
        if($users) {
            return UserResource::collection($users);
        }else {
            return response()->json(['message'=>'No users activated yet']);
        }
    }
}
