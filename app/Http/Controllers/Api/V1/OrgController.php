<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Org;

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
}
