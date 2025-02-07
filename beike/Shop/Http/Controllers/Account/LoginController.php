<?php
/**
 * LoginController.php
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
use Beike\Shop\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoginController extends Controller
{
    /**
     * 显示登录页面
     */
    public function index()
    {
        if (current_customer()) {
            return redirect()->route('shop.account.index');
        }

        $loginData = [
            'social_buttons' => hook_filter('login.social.buttons', []),
        ];

       return view('account.login', $loginData);
    }

    /**
     * 处理用户登录
     */
    public function login(LoginRequest $request)
    {
        try {
            hook_action('shop.account.login.before', ['request_data' => $request->all()]);

            $guestCartProduct = CartRepo::allCartProducts(0);

            // **验证用户账号 & 密码**
            if (!Auth::guard(Customer::AUTH_GUARD)->attempt($request->only('email', 'password'))) {
                throw new NotAcceptableHttpException(trans('shop/login.email_or_password_error'));
            }

            // **获取当前登录用户**
            $customer = current_customer();
            if (!$customer) {
                throw new NotFoundHttpException(trans('shop/login.empty_customer'));
            }

            // **检查用户状态**
            if ($customer->active != 1) {
                Auth::guard(Customer::AUTH_GUARD)->logout();
                throw new NotFoundHttpException(trans('shop/login.customer_inactive'));
            }

            if ($customer->status == 'pending') {
                Auth::guard(Customer::AUTH_GUARD)->logout();
                throw new NotFoundHttpException(trans('shop/login.customer_not_approved'));
            }

            if ($customer->status == 'rejected') {
                Auth::guard(Customer::AUTH_GUARD)->logout();
                throw new NotFoundHttpException(trans('shop/login.customer_rejected'));
            }

            // **合并购物车**
            CartRepo::mergeGuestCart($customer, $guestCartProduct);

            hook_action('shop.account.login.after', ['customer' => $customer]);

            return response()->json(['message' => trans('shop/login.login_successfully')], 200);

        } catch (NotAcceptableHttpException $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => 'password'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => 'status'], 400);
        }
    }

    /**
     * 处理用户登出
     */
    public function logout()
    {
        Auth::guard(Customer::AUTH_GUARD)->logout();
        return redirect()->route('shop.login.index');
    }
}
