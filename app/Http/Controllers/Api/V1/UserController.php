<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserActivity;

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

    public function getUsersCount() {
        $users = User::get();
        $user_count = $users->count();

        return response()->json(['users' => $user_count], 200);
    }

    public function userOnline() {
        $newtimestamp = strtotime(date('Y-m-d H:i:s').' - 5 minute');
        $dateTo = date('Y-m-d H:i:s');
        $dateFrom = date('Y-m-d H:i:s', $newtimestamp);

        $user = UserActivity::whereBetween('modified', [$dateFrom, $dateTo])->get();
        $user_online = $user->count();

        return response()->json(['user_online' => $user_online]);
    }
}
