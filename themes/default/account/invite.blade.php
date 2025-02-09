@extends('layout.master')

@section('title', '邀请好友')

@push('header')
<meta property="og:title" content="快来加入我们！">
<meta property="og:description" content="使用我的邀请码注册，享受专属奖励！">
<meta property="og:image" content="{{ url('/qrcode?data=' . urlencode($inviteLink)) }}">
<meta property="og:url" content="{{ $inviteLink }}">
@endpush

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">邀请好友</h1>

    <div class="card shadow-sm p-4">
        <p>您的专属邀请码：</p>
        @if(isset($customer))
            <h3 class="text-primary fw-bold text-center">{{ $customer->invite_code }}</h3>
        @else
            <h3 class="text-danger fw-bold text-center">邀请码未生成</h3>
        @endif

        <p class="mt-3">邀请链接：</p>
        <div class="input-group mb-3">
            <input type="text" id="inviteLink" class="form-control" value="{{ $inviteLink }}" readonly>
            <button class="btn btn-outline-primary" onclick="copyInvite()">复制</button>
        </div>

        {{-- 二维码生成部分 --}}
        <div class="text-center mt-4">
            <h3>邀请二维码：</h3>
            <img src="{{ url('/qrcode?data=' . urlencode($inviteLink)) }}" 
                 alt="邀请二维码" class="rounded shadow-sm">
        </div>

        {{-- 分享按钮 --}}
        <div class="text-center mt-4">
            <p class="fw-bold">分享到：</p>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($inviteLink) }}" class="btn btn-primary mx-1" target="_blank">
                <i class="bi bi-facebook"></i> Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($inviteLink) }}&text=快来加入我们！" class="btn btn-info mx-1" target="_blank">
                <i class="bi bi-twitter"></i> Twitter
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($inviteLink) }}" class="btn btn-secondary mx-1" target="_blank">
                <i class="bi bi-telegram"></i> Telegram
            </a>
        </div>
    </div>
</div>

@push('add-scripts')
<script>
function copyInvite() {
    let copyText = document.getElementById("inviteLink");
    copyText.select();
    document.execCommand("copy");
    alert("已复制邀请链接！");
}
</script>
@endpush
@endsection
