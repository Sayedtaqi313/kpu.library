<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\setBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\ReserveResource;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Reserve;
use Carbon\Carbon;
class ReserveController extends Controller
{

   public function getAllReserves(Request $request) {
      $request->merge(['get_inactive_users'=>'yes']);
      $reserves = Reserve::where('status','=','inactive')->get();
      return ReserveResource::collection($reserves);

   }

   public function setBook(setBookRequest $request,string $id) {
      $reserve = Reserve::find($id);
      if(!$reserve) {
         return response()->json(['message'=>'Inavliad request']);
      }
      if($reserve) {
         $reserve->status = "active";
         $reserve->save();
         $reserve->duration()->create([
            'res_id' => $reserve->id,
            'borrowed_at' => Carbon::now()->format('Y-m-d'),
            'return_by' => $request->return_by
         ]);
         
      }

      return response()->json(['message' => 'Book reserved Successfully']);
   }

   public function getReservedBookUserById(Request $request, string $id) {
      $request->merge(['getUnactivated_user_detail'=>'yes']);
      $reserve = Reserve::find($id);
      return UserResource::make($reserve->user);

   }
   public function getReservedBookDetailById(Request $request, string $id) {
      $request->merge(['detial'=>'yes']);
      $reserve = Reserve::find($id);
      return BookResource::make($reserve->book);

   }

}