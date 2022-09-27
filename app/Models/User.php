<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class User extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'org_id', 'name', 'username', 'fullname', 'role_id', 'isactive'
    ];

    protected $hidden = [
        'password'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function org() {
        return $this->belongsTo(Org::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function user_activity() {
        return $this->hasOne(UserActivity::class);
    }
}
