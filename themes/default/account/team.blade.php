@extends('layout.master')

@section('body-class', 'page-account')

@section('content')
<x-shop-breadcrumb type="static" value="account.team" />

<div class="container">
    <div class="row">
        <x-shop-sidebar />
        <div class="col-12 col-md-9">
            {{-- 团队概况 --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">我的团队</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-4">
                            <h4 class="fw-bold text-primary">{{ $totalMembers }}</h4>
                            <p class="text-muted">团队总人数</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <h4 class="fw-bold text-warning">{{ $goldMembers }}</h4>
                            <p class="text-muted">黄金会员人数</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <h4 class="fw-bold text-danger">{{ $diamondMembers }}</h4>
                            <p class="text-muted">钻石会员人数</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 团队成员列表 --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">团队成员</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>姓名</th>
                                    <th>会员等级</th>
                                    <th>邀请时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($directMembers as $member)
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>
                                            @if ($member->customer_group_id == 1)
                                                普通会员
                                            @elseif ($member->customer_group_id == 2)
                                                黄金会员
                                            @elseif ($member->customer_group_id == 3)
                                                钻石会员
                                            @else
                                                未知等级
                                            @endif
                                        </td>
                                        <td>{{ $member->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 分页导航 --}}
                    <div class="mt-3">
                        {{ $directMembers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
