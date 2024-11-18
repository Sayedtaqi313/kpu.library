<?php

namespace App\Http\Controllers\Api\BackControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\setBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\ReserveResource;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Reserve;
use App\Models\Cart;
use Carbon\Carbon;

class ReserveController extends Controller
{

   public function getAllReserves(Request $request)
   {
      $request->merge(['get_inactive_users' => 'yes']);
      $reserves = Reserve::where('status', '=', 'inactive')->get();
      return ReserveResource::collection($reserves);
   }

   public function setBook(setBookRequest $request, string $id)
   {
      $reserve = Reserve::find($id);
      if (!$reserve) {
         return response()->json(['message' => 'ID وجود ندارد']);
      }
      if ($reserve) {
         $reserve->status = "active";
         $reserve->save();
         $reserve->duration()->create([
            'res_id' => $reserve->id,
            'borrowed_at' => Carbon::now()->format('Y-m-d'),
            'return_by' => $request->return_by
         ]);
      }

      return response()->json(['message' => 'کتاب موفقانه رزرو شد']);
   }

   public function getReservedBookUserById(Request $request, string $id)
   {
      $request->merge(['getInactivated_user_detail' => 'yes']);
      $reserve = Reserve::find($id);
      return UserResource::make($reserve->user);
   }
   public function getReservedBookDetailById(Request $request, string $id)
   {
      $request->merge(['detail' => 'yes']);
      $reserve = Reserve::find($id);
      return BookResource::make($reserve->book);
   }

   public function usersGotBook(Request $request)
   {
      $request->merge(['get_users_got_book' => 'yes']);
      $gotBooksUsers = Reserve::where('status', 'active')->get();
      if (!$gotBooksUsers) {
         return response()->json(['message' => 'هیچ یوسر پیدا نشد']);
      }

      return ReserveResource::collection($gotBooksUsers);
   }

   public function userReturnBook(Request $request, Reserve $reserve)
   {
      $reserve->delete();
      return response()->json(['message' => 'یسر کتاب را موففانه پس اورد']);
   }
}
