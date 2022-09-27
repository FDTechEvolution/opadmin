<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Org extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name',
        'code',
        'mobileno',
        'isactive',
        'line_notify_token'
    ];

    protected $hidden = [

    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function user() {
        return $this->hasMany(User::class)->with('user_activity', 'role');
    }

    public function order() {
        return $this->hasMany(Order::class);
    }
}
