<?php

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Shop\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * 我的团队页面
     */
    public function index(Request $request)
    {
        $customer = Auth::guard('customers')->user();

        if (!$customer) {
            abort(403, '未登录');
        }

        // 获取直接下级成员，分页
        $directMembers = $customer->invites()->paginate(10);

        // 获取团队统计数据
        $teamStats = $customer->invites()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN customer_group_id = 2 THEN 1 ELSE 0 END) as gold,
                SUM(CASE WHEN customer_group_id = 3 THEN 1 ELSE 0 END) as diamond
            ')
            ->first();

        $totalMembers = $teamStats->total;
        $goldMembers = $teamStats->gold;
        $diamondMembers = $teamStats->diamond;

        return view('themes.default.account.team', compact('directMembers', 'totalMembers', 'goldMembers', 'diamondMembers'));
    }
}
