<?php

namespace App\Http\Controllers\Api\frontControllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\CartResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Cart;


class CartController extends Controller
{

   public function getAllCartBook(Request $request) {
       $carts = Cart::where('user_id',auth()->user()->id)->get();
       return CartResource::collection($carts);
      
   }
   public function addBookToCart(Request $request,string $id) {
      $book = Book::find($id);
      if(!$book) {
        return response()->json(['message' => 'Book not Found'],Response::HTTP_NOT_FOUND);
      }
      
      $carts = Cart::where('user_id',auth()->user()->id)->get();
      if($carts) {
        foreach($carts as $cart) {
            if($cart->book_id == $id){
                return response()->json(['message' => 'This Book added to cart already']);
            }
        }
      }
     
      Cart::create([
          'user_id' => auth()->user()->id,
          'book_id' => $book->id,
      ]);

       return response()->json(['message'=>'Book added to cart']);
   }

   public function deleteCartBook(string $id) {
       $book = Book::find($id);
       if(!$book) {
        return response()->json(['message' => 'Book not Found'],Response::HTTP_NOT_FOUND);
      }
      
        $carts = Cart::where('user_id',auth()->user()->id)->get();
        foreach($carts as $cart) {
            if($cart->book_id == $id) {
                $cart->delete();
            }
        }
        return response()->json(['message' => 'Removed from cart']);

   }
}
