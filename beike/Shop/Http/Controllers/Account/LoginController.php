<?php

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Repositories\CartRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
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

        return view('account.login', [
            'social_buttons' => hook_filter('login.social.buttons', []),
        ]);
    }

    /**
     * 处理用户登录请求
     */
    public function login(LoginRequest $request)
    {
        try {
            hook_action('shop.account.login.before', ['request_data' => $request->all()]);

            // **确保 CSRF 令牌存在**
            if (!$request->has('_token')) {
                throw new NotAcceptableHttpException('CSRF Token Missing');
            }

            // **访客购物车商品**
            $guestCartProduct = CartRepo::allCartProducts(0);

            // **尝试登录**
            if (!Auth::guard(Customer::AUTH_GUARD)->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->filled('remember'))) {
                throw new NotAcceptableHttpException(trans('shop/login.email_or_password_error'));
            }

            // **获取当前用户**
            $customer = Auth::guard(Customer::AUTH_GUARD)->user();
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

            return response()->json([
                'message' => trans('shop/login.login_successfully'),
                'redirect' => route('shop.account.index')
            ], 200);

        } catch (NotAcceptableHttpException $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => 'password'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => 'status'], 400);
        }
    }

    /**
     * 处理用户登录请求（修正 `store()` 方法）
     */
    public function store(LoginRequest $request)
    {
        return $this->login($request);
    }

    /**
     * 处理用户登出
     */
    public function logout(Request $request)
    {
        Auth::guard(Customer::AUTH_GUARD)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('shop.login.index');
    }
}
