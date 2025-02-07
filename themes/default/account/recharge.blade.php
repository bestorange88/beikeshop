@extends('layout.master')

@section('body-class', 'page-recharge')

@section('content')
<x-shop-breadcrumb type="static" value="account.recharge" />

<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">账户充值</h5>
        </div>
        <div class="card-body">
            <form action="{{ shop_route('account.recharge.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">充值金额</label>
                    <input type="number" class="form-control" id="amount" name="amount" placeholder="输入充值金额" required>
                </div>
                <div class="mb-3">
                    <label for="payment-method" class="form-label">选择支付方式</label>
                    <select class="form-control" id="payment-method" name="payment_method" required>
                        <option value="paypal">PayPal</option>
                        <option value="credit_card">信用卡</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">确认充值</button>
            </form>
        </div>
    </div>
</div>
@endsection
