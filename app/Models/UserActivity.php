<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class UserActivity extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'modified'
    ];

    protected $hidden = [

    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
