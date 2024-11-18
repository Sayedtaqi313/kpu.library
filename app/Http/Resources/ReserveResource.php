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
        if ($request->has('get_inactive_users')) {
            return [
                'id' => $this->id,
                'firstName' => $this->user->userable->firstName,
                'total_book' => $this->book->stock->total,
                'remain_book' => $this->book->stock->remain,
                'user_status' => $this->user->status,
                'book_status' => $this->book->barrow == "yes" ? "barrowable" : "reservable",
                'nic' => $this->user->userable->nic,
                'nin' => $this->user->userable->nin,
                'book' => $this->book->title,
                'book_code' => $this->book->code,
                'section' => $this->book->section->section,
                'shelf' => $this->book->section->shelf
            ];
        } else if ($request->has('get_users_got_book')) {
            return [
                'id' => $this->id,
                'firstName' => $this->user->userable->firstName,
                'user_status' => $this->user->status,
                'nic' => $this->user->userable->nic,
                'nin' => $this->user->userable->nin,
                'book' => $this->book->title,
                'book_status' => $this->book->barrow == "yes" ? "barrowable" : "reservable",
                'return_date' => $this->duration->return_by
            ];
        } else {
            return [
                'book_title' => $this->book->title,
                'book_image' => asset($this->book->image->image),
                'return_date' => $this->duration->return_by,
            ];
        }
    }
}
