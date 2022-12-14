<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Order extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'orderdate',
        'status',
        'org_id'
    ];

    protected $hidden = [

    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
