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
                'nic' => $this->user->userable->nic,
                'book' => $this->book->title,
                'tody' => Carbon::now()->format('Y-m-d')
            ];
        }
    }
}
