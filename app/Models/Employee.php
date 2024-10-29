<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['name','email','password','type'];
    public function permissions() {
        return $this->hasMany(Permission::class,'emp_id');
    }
}
