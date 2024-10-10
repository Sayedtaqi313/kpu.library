<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\BookResource;

class StudentProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            "user_id" => $this->id,
            "firstName" => $this->userable->firstName,
            "lastName" => $this->userable->lastName,
            "email" => $this->email,
            "type" => $this->type,
            "status" => $this->status,
            "nin" => $this->userable->nin,
            "nic" => $this->userable->nic,
            "phone" => $this->userable->phone,
            "image" => $this->userable->image->image,
            "current_residence" => $this->userable->current_residence,
            "original_residence" => $this->userable->original_residence,
            "faculty" => $this->userable->faculty->name,
            "department" => $this->userable->department->name,
            "books" => ReserveResource::collection($this->reserves)
            
        ];
    }
}
