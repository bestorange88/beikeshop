<?php
namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\CustomerRepo;
use Beike\Repositories\OrderRepo;
use Beike\Repositories\CustomerRewardRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\ForgottenRequest;
use Beike\Shop\Http\Resources\Account\OrderSimpleList;
use Beike\Shop\Http\Resources\CustomerResource;
use Beike\Models\CustomerGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * 个人中心首页
     */
    public function index()
    {
        $customer = current_customer();
        $this->upgradeCustomerLevel($customer);

        $data = [
            'customer'      => new CustomerResource($customer),
            'latest_orders' => OrderSimpleList::collection(OrderRepo::getLatestOrders($customer, 10)),
            'customerLevel' => $this->getCustomerLevel($customer),
        ];

        return view()->file(base_path('themes/default/account/account.blade.php'), $data);
    }

    /**
     * 获取用户等级名称
     */
    private function getCustomerLevel($customer): string
    {
        return DB::table('customer_group_descriptions')
            ->where('customer_group_id', $customer->customer_group_id)
            ->value('name') ?? '未知等级';
    }

    /**
     * 处理会员升级逻辑
     */
    private function upgradeCustomerLevel($customer)
    {
        if (!$customer) return;

        $invitedCount = $customer->invites()->where('customer_group_id', CustomerGroup::NORMAL)->count();
        $hasPaidFee = $customer->transactions()->where('reason', 'membership_fee')->where('amount', 1000)->exists();

        // **升级为黄金会员**
        if ($customer->customer_group_id == CustomerGroup::NORMAL && ($invitedCount >= 2 || $hasPaidFee)) {
            $customer->customer_group_id = CustomerGroup::GOLD;
            $customer->invite_code = $this->generateInviteCode();
            $customer->save();

            $this->distributeInviteReward($customer);
        }

        // **升级为钻石会员**
        $goldMembers = $customer->invites()->where('customer_group_id', CustomerGroup::GOLD)->count();
        if ($customer->customer_group_id == CustomerGroup::GOLD && $goldMembers >= 2) {
            $customer->customer_group_id = CustomerGroup::DIAMOND;
            $customer->save();

            // **脱离原团队并留人**
            $this->leaveTeamWithGoldenMembers($customer);
        }
    }

    /**
     * 脱离原团队并保留 2 名黄金会员
     */
    private function leaveTeamWithGoldenMembers($customer)
    {
        // 获取黄金会员前两名
        $goldenMembers = $customer->invites()
            ->where('customer_group_id', CustomerGroup::GOLD)
            ->limit(2)
            ->get();

        // 留下这两名黄金会员，其余关系解除
        DB::table('customers')
            ->where('inviter_id', $customer->id)
            ->whereNotIn('id', $goldenMembers->pluck('id'))
            ->update(['inviter_id' => null]);

        // 自己脱离团队
        DB::table('customers')
            ->where('id', $customer->id)
            ->update(['team_id' => null]);
    }

    /**
     * 生成唯一的邀请码
     */
    private function generateInviteCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (DB::table('customers')->where('invite_code', $code)->exists());

        return $code;
    }

    /**
     * 处理邀请奖励
     */
    private function distributeInviteReward($customer)
    {
        $inviter = $customer->invitedBy;
        if (!$inviter) return;

        $inviteRank = $inviter->invites()->where('customer_group_id', CustomerGroup::GOLD)->count();
        $rewardRate = $inviteRank < 2 ? 0.2 : 0.6;
        $rewardAmount = 1000 * $rewardRate;

        CustomerRewardRepo::create([
            'customer_id' => $inviter->id,
            'amount' => $rewardAmount,
            'type' => 'invite_bonus'
        ]);
    }

    /**
     * 计算购物返现
     */
    public function cashback()
    {
        $customer = Auth::guard('customers')->user();
        if (!$customer) {
            abort(403, '未登录');
        }

        $cashbacks = CustomerRewardRepo::getCashbacks($customer->id);
        return view('themes.default.account.cashback', compact('cashbacks'));
    }

    /**
     * 计算购物返现金额
     */
    public function calculateCashback($customer, $order)
    {
        $rate = [
            '普通会员' => 0.0003,
            '黄金会员' => 0.0005,
            '钻石会员' => 0.002
        ];

        $level = $this->getCustomerLevel($customer);
        $cashback = $order->amount * ($rate[$level] ?? 0);

        CustomerRewardRepo::create([
            'customer_id' => $customer->id,
            'amount' => $cashback,
            'type' => 'cashback'
        ]);
    }

    /**
     * 获取用户团队成员
     */
    public function team()
    {
        $customer = Auth::guard('customers')->user();
        if (!$customer) {
            abort(403, '未登录');
        }

        $teamMembers = $customer->invites()
            ->where(function ($query) use ($customer) {
                if ($customer->customer_group_id == CustomerGroup::DIAMOND) {
                    $query->where('parent_id', $customer->id);
                } else {
                    $query->where('grand_parent_id', $customer->id);
                }
            })
            ->get();

        return view('themes.default.account.team', compact('teamMembers'));
    }

    /**
     * 获取邀请码
     */
    public function getInviteCode()
    {
        $customer = Auth::guard('customers')->user();
        if (!$customer) {
            abort(403, '未登录');
        }

        return response()->json(['invite_code' => $customer->invite_code]);
    }
}
