{{-- 邀请好友页面 --}}
@extends('layout.master')

@section('content')
<div class="container">
    <h1>邀请好友</h1>
    <p>您的专属邀请码：<strong>{{ $customer->invite_code }}</strong></p>
    <p>邀请链接：</p>
    <input type="text" class="form-control mb-3" value="{{ $inviteLink }}" readonly>
    <button class="btn btn-primary" onclick="copyInvite()">复制邀请链接</button>

    {{-- 二维码生成部分 --}}
    <div class="mt-4">
        <h3>邀请二维码：</h3>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($inviteLink) }}" alt="邀请二维码">
    </div>
</div>

<script>
function copyInvite() {
    const copyText = document.querySelector('input.form-control');
    copyText.select();
    document.execCommand("copy");
    alert("已复制邀请链接");
}
</script>
@endsection
