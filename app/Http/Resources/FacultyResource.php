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
        if ($request->has('faculties_with_departments')) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'departments' => DepartmentResource::collection($this->departments)
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
