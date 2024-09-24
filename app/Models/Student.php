<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['firstName', 'lastName', 'phone', 'nic', 'current_residence', 'original_residence', 'fac_id', 'dep_id'];
    public function user() {
        return $this->morphOne(User::class,'userable');
    }
}
