<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class UserController extends Controller
{
    public function getUsers() {
        $users = User::with(['org', 'role'])->get();

        return response()->json(['user' => $users, 'error' => null], 200);
    }

    public function updateStatus($user_id) {
        $user = User::find($user_id);
        $status = $user->isactive == 'Y' ? 'N' : 'Y';
        $user->update(['isactive' => $status]);
        
        if($user) return response()->json(['error' => false], 200);
        return response()->json(['error' => true], 400);
    }
}
