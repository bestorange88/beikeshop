@extends('layout.master')

@section('body-class', 'page-withdraw')

@section('content')
<x-shop-breadcrumb type="static" value="account.withdraw" />

<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">账户提现</h5>
        </div>
        <div class="card-body">
            <form action="{{ shop_route('account.withdraw.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">提现金额</label>
                    <input type="number" class="form-control" id="amount" name="amount" placeholder="输入提现金额" required>
                </div>
                <div class="mb-3">
                    <label for="bank-account" class="form-label">银行账户</label>
                    <input type="text" class="form-control" id="bank-account" name="bank_account" placeholder="输入银行账户" required>
                </div>
                <button type="submit" class="btn btn-primary">确认提现</button>
            </form>
        </div>
    </div>
</div>
@endsection
