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
        if ($request->has('type')) {
            return [
                "id" => $this->id,
                "name" => $this->userable->firstName,
                "email" => $this->email,
                "status" => $this->status,
                "type" => $this->type
            ];
        } else if ($request->has('getInactivatedUsers')) {
            return [
                "id" => $this->id,
                "firstName" => $this->userable->firstName,
                "lastName" => $this->userable->lastName,
                "email" => $this->email,
                "type" => $this->type,
                "status" => $this->status,
                "nin" => $this->userable->nin,
                "nic" => $this->userable->nic,
                "phone" => $this->userable->phone,
                "image" => asset($this->userable->image->image),
                "current_residence" => $this->userable->current_residence,
                "original_residence" => $this->userable->original_residence,
                "faculty" => Department::find($this->userable->fac_id)->first()->name,
                "department" => Faculty::find($this->userable->dep_id)->first()->name,
            ];
        } else if ($request->has('getInactivated_user_detail')) {
            return [
                "id" => $this->id,
                "firstName" => $this->userable->firstName,
                "lastName" => $this->userable->lastName,
                "email" => $this->email,
                "type" => $this->type,
                "status" => $this->status,
                "nin" => $this->userable->nin,
                "nic" => $this->userable->nic,
                "phone" => $this->userable->phone,
                "image" => asset($this->userable->image->image),
                "current_residence" => $this->userable->current_residence,
                "original_residence" => $this->userable->original_residence,
                "faculty" => Department::find($this->userable->fac_id)->first()->name,
                "department" => Faculty::find($this->userable->dep_id)->first()->name,

            ];
        }

        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'type' => $this->type
        ];
    }
}
