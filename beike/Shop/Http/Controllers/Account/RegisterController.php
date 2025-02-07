<?php
/**
 * RegisterController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @modified   2024-02-02
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Repositories\CartRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\RegisterRequest;
use Beike\Shop\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * 显示注册页面
     */
    public function index()
    {
        return view('themes.default.account.register'); // 确保视图路径正确
    }

    /**
     * 处理注册逻辑（支持邀请码）
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $inviteCode  = $request->input('invite_code'); // 获取邀请码

        // 确保邀请码存在
        $inviter = Customer::where('invite_code', $inviteCode)->first();
        if (!$inviter) {
            return response()->json(['message' => trans('shop/register.invalid_invite_code')], 400);
        }

        // 创建新会员
        $customer = new Customer();
        $customer->email = $credentials['email'];
        $customer->password = Hash::make($credentials['password']);
        $customer->inviter_id = $inviter->id;
        $customer->invite_code = $this->generateInviteCode(); // 生成邀请码
        $customer->member_level = 'normal'; // 默认普通会员
        $customer->status = 'active';
        $customer->save();

        // 处理购物车合并
        $guestCartProduct = CartRepo::allCartProducts(0);
        Auth::guard(Customer::AUTH_GUARD)->login($customer);
        CartRepo::mergeGuestCart($customer, $guestCartProduct);

        // 会员升级逻辑
        $this->checkAndUpgradeMember($customer);

        return response()->json(['message' => trans('shop/register.success')], 200);
    }

    /**
     * 生成唯一的邀请码
     */
    private function generateInviteCode(): string
    {
        do {
            $code = strtoupper(Str::random(8)); // 生成8位大写邀请码
        } while (Customer::where('invite_code', $code)->exists());

        return $code;
    }

    /**
     * 检查会员升级逻辑
     */
    private function checkAndUpgradeMember(Customer $customer)
    {
        $inviter = $customer->inviter;

        // 如果邀请者是黄金会员，检查是否可以升级为钻石会员
        if ($inviter && $inviter->member_level === 'gold') {
            $goldMembers = $inviter->invites()->where('member_level', 'gold')->count();

            if ($goldMembers >= 2) {
                $inviter->upgradeToDiamond();
            }
        }
    }
}
