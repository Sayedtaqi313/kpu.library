<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    
    public $permissionArr = [
        'createUser',
        'updateUser',
        'deleteUser',
        'createBook',
        'updateBook',
        'deleteBook',
        'createCategory',
        'updateCategory',
        'deleteCategory',
        'createFaculty',
        'updateFaculty',
        'deleteFaculty',
        'createDepartment',
        'updateDepartment',
        'deleteDepartment',
        'createSection',
        'updateSection',
        'deleteSection',
        'activateUser',
        'activateReserve',
        'createEmployee',
        'updateEmployee',
        'deleteEmployee',
        'permissions'

    ];

    public $number = 0;
    public function definition(): array
    {
        
            return [
                "permission" => $this->permissionArr[$this->number++]
            ];
        
    }
}
