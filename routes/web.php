<?php

use Illuminate\Support\Facades\Route;
use Beike\Shop\Http\Controllers\Account\AccountController;
use Beike\Shop\Http\Controllers\Account\RegisterController;
use Beike\Shop\Http\Controllers\Account\LoginController;
use Beike\Shop\Http\Controllers\Account\TeamController;

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

// 用户登录与登出
Route::get('/login', [LoginController::class, 'index'])->name('shop.login.index'); // 显示登录页面
Route::post('/login', [LoginController::class, 'login'])->name('shop.login.store'); // 处理登录请求
Route::post('/logout', [LoginController::class, 'logout'])->name('shop.account.logout'); // 处理登出请求

// =========================== 🔹 会员中心（需登录） ===========================

Route::middleware('auth:customers')->prefix('account')->name('shop.account.')->group(function () {
    // 个人中心首页
    Route::get('/', [AccountController::class, 'index'])->name('index'); 

    // 我的账单
    Route::get('/cashback', [AccountController::class, 'cashback'])->name('cashback'); 

    // 我的团队
    Route::get('/team', [TeamController::class, 'index'])->name('team');

    // 邀请好友功能
    Route::get('/invite-code', [AccountController::class, 'getInviteCode'])->name('invite-code'); 
    Route::get('/invite', [AccountController::class, 'invite'])->name('invite');

    // 会员升级与等级
    Route::get('/levels', [AccountController::class, 'levels'])->name('levels');
    Route::post('/upgrade', [AccountController::class, 'upgradeToGold'])->name('upgrade');
    
    Route::get('/help', function () {
        return view('themes.default.account.help');
    })->name('help');
    // =========================== 🔹 新增功能 ===========================
    
    Route::get('/cart', function () {
        return view('themes.default.cart.index');
    })->name('shop.cart.view');
     
    Route::get('/account/upgrade', [AccountController::class, 'upgradeView'])->name('shop.account.upgrade');

    // 充值功能
    Route::get('/recharge', function () {
        return view('themes.default.account.recharge');
    })->name('recharge');
    Route::post('/recharge', [AccountController::class, 'recharge'])->name('recharge.post');

    // 提现功能
    Route::get('/withdraw', function () {
        return view('themes.default.account.withdraw');
    })->name('withdraw');
    Route::post('/withdraw', [AccountController::class, 'withdraw'])->name('withdraw.post');

    // 我的积分
    Route::get('/points', [AccountController::class, 'points'])->name('points');

    // 售后服务
    Route::get('/rma', [AccountController::class, 'rma'])->name('rma.index');

    // 在线客服
    Route::get('/chat', function () {
        return response()->json(['message' => '正在连接在线客服...']);
    })->name('chat');
});

