<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\Uuids;

class Opadmin extends Authenticatable implements JWTSubject
{
    use HasFactory, Uuids, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'name',
    ];

    protected $hidden = [
        'remember_token',
        'created',
        'modified'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }  
}
