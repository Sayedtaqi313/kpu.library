<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Reserve;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'email',
        'password',
        'status',
        'type'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userable() {
        return $this->morphTo();
    }

    public function reserves() {
        return $this->hasMany(Reserve::class,'user_id');
    }

    public function carts() {
        return $this->hasMany(Cart::class,'user_id');
    }

    public function fines() {
        return $this->hasMany(Fine::class,'user_id');
    }

}
