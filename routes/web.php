<?php
use Illuminate\Support\Facades\Route;
use Beike\Shop\Http\Controllers\Account\AccountController;
use Beike\Shop\Http\Controllers\Account\RegisterController;
use Beike\Shop\Http\Controllers\Account\LoginController;

// =========================== 🔹 测试路由 ===========================

Route::get('/test', function () {
    echo __FILE__;
})->name('test');

// =========================== 🔹 商城前端路由 ===========================

Route::get('/', function () {
    return view('themes.default.home');
})->name('shop.home');

Route::get('/category/{slug}', function ($slug) {
    return view('themes.default.category', compact('slug'));
})->name('shop.category');

Route::get('/checkout', function () {
    return view('themes.default.checkout');
})->name('shop.checkout');

// =========================== 🔹 用户注册 & 登录相关路由 ===========================
// 用户注册
Route::get('/register', [RegisterController::class, 'index'])->name('shop.register'); // 显示注册页面
Route::post('/register', [RegisterController::class, 'store'])->name('shop.register.post'); // 提交注册数据

// 用户登录
Route::get('/login', [LoginController::class, 'index'])->name('shop.login.index'); // 显示登录页面
Route::post('/login', [LoginController::class, 'login'])->name('shop.account.login'); // 处理登录请求
Route::post('/logout', [LoginController::class, 'logout'])->name('shop.account.logout'); // 处理登出请求

// =========================== 🔹 会员中心（需登录） ===========================
Route::middleware('auth:customers')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('shop.account.index'); // 个人中心首页
    Route::get('/account/cashback', [AccountController::class, 'cashback'])->name('shop.account.cashback'); // 计算购物返现
    Route::get('/account/team', [AccountController::class, 'team'])->name('shop.account.team'); // 获取用户团队成员
    Route::get('/account/invite-code', [AccountController::class, 'getInviteCode'])->name('shop.account.invite-code'); // 获取邀请码
});
    // =========================== 🔹 新增功能 ===========================

    // 充值页面
    Route::get('/account/recharge', function () {
        return view('themes.default.account.recharge');
    })->name('shop.account.recharge');
    Route::post('/account/recharge', [AccountController::class, 'recharge'])->name('shop.account.recharge.post');

    // 提现页面
    Route::get('/account/withdraw', function () {
        return view('themes.default.account.withdraw');
    })->name('shop.account.withdraw');
    Route::post('/account/withdraw', [AccountController::class, 'withdraw'])->name('shop.account.withdraw.post');

    // 用户积分
    Route::get('/account/points', [AccountController::class, 'points'])->name('shop.account.points');

    // 售后服务
    Route::get('/account/rma', [AccountController::class, 'rma'])->name('shop.account.rma.index');

    // 在线客服
    Route::get('/account/chat', function () {
        return response()->json(['message' => '正在连接在线客服...']);
    })->name('shop.account.chat');
});
