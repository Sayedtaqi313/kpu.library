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
            "id" => $this->id,
            "email" => $this->email,
            "phone" => $this->userable->phone,
            "image" => asset($this->userable->image->image),
            "books" => ReserveResource::collection($this->reserves)

        ];
    }
}
