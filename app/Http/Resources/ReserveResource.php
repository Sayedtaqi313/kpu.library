<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ReserveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($request->has('get_inactive_users')){
            return [
                'firstName' => $this->user->userable->firstName,
                'total_book' => $this->book->stock->total,
                'reamin_book' => $this->book->stock->remain,
                'user_status' => $this->user->status,
                'book_status' => $this->book->barrow == "yes" ? "barrowable" : "reservable",
                'nic' => $this->user->userable->nic,
                'nin' => $this->user->userable->nin,
                'book' => $this->book->title,
            ];
        }
    }
}
