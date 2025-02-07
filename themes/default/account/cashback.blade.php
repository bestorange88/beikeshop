{{-- 路径: beikeshop/themes/default/account/cashback.blade.php --}}

@extends('layouts.master')

@section('title', '购物返现记录')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            @include('account.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>购物返现记录</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>订单号</th>
                                <th>金额</th>
                                <th>返现金额</th>
                                <th>时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (current_customer()->rewards as $reward)
                                <tr>
                                    <td>{{ $reward->order_id }}</td>
                                    <td>¥{{ number_format($reward->order_amount, 2) }}</td>
                                    <td>¥{{ number_format($reward->cashback_amount, 2) }}</td>
                                    <td>{{ $reward->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (current_customer()->rewards->isEmpty())
                        <p class="text-muted text-center">暂无返现记录</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
