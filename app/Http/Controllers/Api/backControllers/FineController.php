<?php

namespace App\Http\Controllers\Api\backControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FineResource;
use Illuminate\Http\Request;
use App\Models\Fine;

class FineController extends Controller
{
    public function getUnPaidUsers(Request $request) {
        $fines = Fine::where('paid','no')->get();
        return FineResource::collection($fines);
    }

    public function payFine(Fine $fine) {
       $fine->paid = "yes";
       $fine->save();
       return response()->json(['message'=>'Amount paid Successfully']);
    }

    public function paidusers(Request $request) {
        $request->merge(['paid_users'=>'yes']);
        $fines = Fine::where('paid','yes')->get();
        return FineResource::collection($fines);
    }
}
