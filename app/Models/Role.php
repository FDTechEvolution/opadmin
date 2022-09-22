<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Role extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name', 'menu_order', 'menu_product', 'menu_user', 'menu_accounting', 'menu_report', 'menu_stock'
    ];

    protected $hidden = [

    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
