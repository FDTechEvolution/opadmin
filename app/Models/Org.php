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
        'isactive'
    ];

    protected $hidden = [
        'modified'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function user() {
        return $this->hasMany(User::class);
    }
}
