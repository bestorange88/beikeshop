{{-- 路径: beikeshop/themes/default/account/account.blade.php --}}
@extends('layout.master')

@section('body-class', 'page-account')

@section('content')
<x-shop-breadcrumb type="static" value="account.index" />

<div class="container">
    <div class="row">
        <x-shop-sidebar />

        <div class="col-12 col-md-9">
            {{-- 提示信息 --}}
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
                </div>
            @endif

            {{-- 会员信息卡片 --}}
            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="card-title mb-0">会员中心</h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggle-info">
                        <i class="bi bi-eye"></i> <!-- 控制信息隐藏/显示 -->
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        {{-- 头像上传 --}}
                        <div class="me-3 position-relative">
                            <label for="upload-avatar" class="cursor-pointer position-relative">
                                <img id="avatar-preview" src="{{ current_customer()->avatar ?? '/images/default-avatar.png' }}" 
                                     class="rounded-circle border shadow" width="80" height="80" style="cursor:pointer;">
                                <input type="file" id="upload-avatar" class="d-none" onchange="updateAvatar(this)">
                                <span class="position-absolute top-100 start-50 translate-middle badge bg-dark text-white small">更换头像</span>
                            </label>
                        </div>

                        {{-- 用户信息 --}}
                        <div id="user-info">
                            <h5 class="mb-1 fw-bold">{{ current_customer()->name }}</h5>
                            <p class="text-muted mb-2"><i class="bi bi-award-fill text-warning"></i> 会员等级：
                                <span class="badge bg-primary">{{ $customerLevel }}</span>
                            </p>
                            <p class="text-muted mb-1"><i class="bi bi-person-check text-success"></i> 真实姓名：
                                <span class="info-content">{{ current_customer()->real_name ?? '未实名' }}</span>
                            </p>
                            <p class="text-muted mb-1"><i class="bi bi-wallet2 text-info"></i> 账户余额：
                                <span class="info-content">{{ number_format(current_customer()->balance ?? 0, 2) }} 元</span>
                            </p>
                            <p class="text-muted mb-0"><i class="bi bi-stars text-danger"></i> 积分：
                                <span class="info-content">{{ current_customer()->points ?? 0 }} 分</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 会员功能模块 --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">会员功能</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
    {{-- ✅ 确保所有 shop_route 存在 --}}
    <div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">会员功能</h5>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap text-center">

            {{-- 🔹 我的团队 --}}
            <div class="col-6 col-md-3 mb-3">
                <a href="{{ route('shop.account.team') }}" class="d-block p-3 text-dark">
                    <div class="icon mb-2"><i class="bi bi-people fs-2"></i></div>
                    <div class="text">我的团队</div>
                </a>
            </div>

            {{-- 🔹 我的购物车（新增加） --}}
            <a href="{{ route('shop.account.shop.cart.view') }}" class="d-block p-3 text-dark">
                <div class="icon mb-2"><i class="bi bi-cart fs-2"></i></div>
                <div class="text">我的购物车</div>
            </a>


            {{-- 🔹 会员升级（新增加） --}}
            <div class="col-6 col-md-3 mb-3">
                <a href="{{ route('shop.account.upgrade') }}" class="d-block p-3 text-dark">
                    <div class="icon mb-2"><i class="bi bi-award fs-2"></i></div>
                    <div class="text">会员升级</div>
                </a>
            </div>

            {{-- 🔹 邀请好友 --}}
            <div class="col-6 col-md-3 mb-3">
                <a href="{{ route('shop.account.invite') }}" class="d-block p-3 text-dark">
                    <div class="icon mb-2"><i class="bi bi-person-plus fs-2"></i></div>
                    <div class="text">邀请好友</div>
                </a>
            </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.cashback') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-cash fs-2 text-success"></i></div>
            <div class="text">我的账单</div>
        </a>
    </div>

    {{-- ✅ 移除错误路由 'account.levels' --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.help') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-question-circle fs-2 text-danger"></i></div>
            <div class="text">帮助中心</div>
        </a>
    </div>

    {{-- ✅ 充值 --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.recharge') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-wallet2 fs-2"></i></div>
            <div class="text">充值</div>
        </a>
    </div>

    {{-- ✅ 提现 --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.withdraw') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-bank fs-2"></i></div>
            <div class="text">提现</div>
        </a>
    </div>

    {{-- ✅ 会员积分 --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.points') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-coin fs-2"></i></div>
            <div class="text">我的积分</div>
        </a>
    </div>

    {{-- ✅ 实名认证 --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('shop.account.rma.index') }}" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-tools fs-2"></i></div>
            <div class="text">实名认证</div>
        </a>
    </div>

    {{-- ✅ 在线客服 --}}
    <div class="col-6 col-md-3">
        <a href="javascript:void(0);" onclick="openChat()" class="d-block text-center text-decoration-none">
            <div class="icon mb-2"><i class="bi bi-chat-left-dots fs-2"></i></div>
            <div class="text">在线客服</div>
        </a>
    </div>
</div>

                </div>
            </div>

            {{-- 订单信息卡片 --}}
            <div class="card account-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">我的订单</h5>
                    <a href="{{ shop_route('account.order.index') }}" class="text-muted">全部订单</a>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-nowrap card-items mb-4 py-3">
                        <a href="{{ shop_route('account.order.index', ['status' => 'unpaid']) }}" class="d-flex flex-column align-items-center">
                            <i class="iconfont fs-2">&#xf12f;</i>
                            <span class="text-muted text-center">待支付</span>
                        </a>
                        <a href="{{ shop_route('account.order.index', ['status' => 'paid']) }}" class="d-flex flex-column align-items-center">
                            <i class="iconfont fs-2">&#xf130;</i>
                            <span class="text-muted text-center">待发货</span>
                        </a>
                        <a href="{{ shop_route('account.order.index', ['status' => 'shipped']) }}" class="d-flex flex-column align-items-center">
                            <i class="iconfont fs-2">&#xf131;</i>
                            <span class="text-muted text-center">待收货</span>
                        </a>
                        <a href="{{ shop_route('account.rma.index') }}" class="d-flex flex-column align-items-center">
                            <i class="iconfont fs-2">&#xf132;</i>
                            <span class="text-muted text-center">售后</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let toggleButton = document.getElementById("toggle-info");
    let infoContents = document.querySelectorAll(".info-content");
    let isHidden = false;

    toggleButton.addEventListener("click", function () {
        isHidden = !isHidden;
        infoContents.forEach(el => {
            el.style.display = isHidden ? "none" : "inline";
        });
        toggleButton.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
    });
});

function updateAvatar(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("avatar-preview").src = e.target.result;
            alert("头像已更新！");
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function openChat() {
    alert("正在打开在线客服...");
}
</script>

@endsection
