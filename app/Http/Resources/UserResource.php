<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Department;
use App\Models\Faculty;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($request->has('type')) {
            return [
                "email" => $this->email,
                "status" => $this->status,
                "type" => $this->type
            ];
        }else if($request->has('getUnativaedUsers')){
            return [
                "user_id" => $this->id,
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "type" => $this->type,
                "status" => $this->status
            ];
        }else if($request->has('getUnactivated_user_detail')){
            return [
                "user_id" => $this->id,
                "firstName" => $this->userable->firstName,
                "lastName" => $this->userable->lastName,
                "email" => $this->email,
                "type" => $this->type,
                "status" => $this->status,
                "nic" => $this->userable->nic,
                "phone" => $this->userable->phone,
                "current_residence" => $this->current_residence,
                "original_residence" => $this->original_residence,
                "faculty" => Department::find($this->userable->fac_id)->first()->name,
                "department" => Faculty::find($this->userable->dep_id)->first()->name,

            ];
        }

    }
}
