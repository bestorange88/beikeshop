@extends('layouts.master')

@section('title', '我的团队')

@section('content')
<div class="container my-5">
    <div class="row">
        {{-- 侧边栏 --}}
        <div class="col-md-3">
            @include('account.sidebar')
        </div>

        {{-- 主体内容 --}}
        <div class="col-md-9">
            <div class="card shadow-sm rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h4 class="mb-0">我的团队</h4>
                    <a href="{{ shop_route('account.invite') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-person-plus"></i> 邀请好友
                    </a>
                </div>

                <div class="card-body">
                    @if ($teamMembers instanceof \Illuminate\Support\Collection && $teamMembers->isEmpty())
                        <div class="text-center p-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">您还没有邀请任何用户</p>
                            <a href="{{ shop_route('account.invite') }}" class="btn btn-outline-primary">
                                立即邀请
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>姓名</th>
                                        <th>邮箱</th>
                                        <th>会员等级</th>
                                        <th>加入时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamMembers as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ \DB::table('customer_group_descriptions')
                                                        ->where('customer_group_id', $member->customer_group_id)
                                                        ->value('name') ?? '未知等级' }}
                                                </span>
                                            </td>
                                            <td>{{ $member->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
