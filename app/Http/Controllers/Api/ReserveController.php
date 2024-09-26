<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\setBookRequest;
use App\Http\Resources\ReserveResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Reserve;
use Carbon\Carbon;
class ReserveController extends Controller
{

   public function getAllReserve(Request $request) {
      $request->merge(['get_inactive_users'=>'yes']);
      $reserves = Reserve::where('status','=','inactive')->get();
      return ReserveResource::collection($reserves);

   }

   public function setBook(setBookRequest $request,string $id) {
      $reserve = Reserve::findOrFail($id);
      if($reserve) {
         $reserve->book->stock->remain = $reserve->book->stock->remain - 1;
         $reserve->book->stock->save();
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

}