@extends('layout.master')

@section('content')
    <div class="container">
        <h2>🏅 会员升级</h2>
        <p>在这里，用户可以选择升级为黄金会员或钻石会员。</p>
        <form action="{{ route('shop.account.upgrade') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning">升级会员</button>
        </form>
    </div>
@endsection
