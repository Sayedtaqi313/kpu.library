<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($request->has('faculty_with_dartments')){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'deparments' => DepartmentResource::collection($this->departments)
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
