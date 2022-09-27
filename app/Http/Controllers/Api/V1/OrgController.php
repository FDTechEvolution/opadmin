<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Org;
use App\Models\User;
use App\Models\Order;

class OrgController extends Controller
{
    public function getOrgs() {
        $org = Org::withCount('user')->get();

        return response()->json(['org' => $org, 'error' => null]);
    }

    public function updateStatus($org_id) {
        $org = Org::find($org_id);
        $status = $org->isactive == 'Y' ? 'N' : 'Y';
        $org->update(['isactive' => $status]);

        if($org) return response()->json(['error' => false], 200);
        return response()->json(['error' => true], 400);
    }

    public function getOrgsCount() {
        $orgs = Org::get();
        $org_count = $orgs->count();
        return response()->json(['orgs' => $org_count]);
    }

    public function orgView($org_id) {
        $org = Org::where('id', $org_id)->with('user')->first();
        $order_month = $this->orgOrderMonth($org_id);
        $order_year = $this->orgOrderYear($org_id);
        return response()->json(['org' => $org, 'order_month' => $order_month, 'order_year' => $order_year, 'error' => false], 200);
    }

    public function userOnline($org_id) {
        $users = User::where('org_id', $org_id)->with('user_activity', 'role')->get();
        return response()->json(['user' => $users], 200);
    }

    public function orderUpdate($org_id) {
        $order = $this->orgOrderMonth($org_id);
        return response()->json(['order' => $order, 'error' => false], 200);
    }

    private function orgOrderMonth($org_id) {
        $order = Order::whereMonth('orderdate', date('m'))->where('status', '!=', 'VO')->where('status', '!=', 'VO_RETURN')->where('org_id', $org_id)->get();
        return $order->count();
    }

    private function orgOrderYear($org_id) {
        $order = Order::whereYear('orderdate', date('Y'))->where('status', '!=', 'VO')->where('status', '!=', 'VO_RETURN')->where('org_id', $org_id)->get();
        return $order->count();
    }
}
