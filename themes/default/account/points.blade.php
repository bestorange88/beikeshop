@extends('layout.master')

@section('body-class', 'page-points')

@section('content')
<x-shop-breadcrumb type="static" value="account.points" />

<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">我的积分</h5>
        </div>
        <div class="card-body">
            <p>当前积分：<strong>{{ $points }}</strong> 分</p>
            <p>购物积分记录：</p>
            @if ($pointHistory->isEmpty())
                <p class="text-muted">暂无积分记录。</p>
            @else
                <ul>
                    @foreach ($pointHistory as $record)
                        <li>{{ $record->description }} - <strong>{{ $record->points }}</strong> 分</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
