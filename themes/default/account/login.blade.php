@extends('layout.master')

@section('body-class', 'page-login')

@push('header')
<script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
<script src="{{ asset('vendor/element-ui/index.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/element-ui/index.css') }}">
@endpush

@section('content')
<x-shop-breadcrumb type="static" value="login.index" />

<div class="container" id="page-login" v-cloak>
  <div class="hero-content pb-3 pb-lg-5 text-center">
    <h1 class="hero-heading">{{ __('shop/login.index') }}</h1>
  </div>

  <div class="login-wrap">
    <div class="card">
      <el-form ref="loginForm" :model="loginForm" :rules="loginRules" :inline-message="true">
        <div class="login-item-header card-header">
          <h6 class="text-uppercase mb-0">{{ __('shop/login.login') }}</h6>
        </div>
        <div class="card-body px-md-2">
          {{-- 登录表单 --}}
          @hookwrapper('account.login.email')
          <el-form-item label="{{ __('shop/login.email') }}" prop="email">
            <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" v-model="loginForm.email" placeholder="{{ __('shop/login.email_address') }}"></el-input>
          </el-form-item>
          @endhookwrapper

          @hookwrapper('account.login.password')
          <el-form-item label="{{ __('shop/login.password') }}" prop="password">
            <el-input @keyup.enter.native="checkedBtnLogin('loginForm')" type="password" v-model="loginForm.password" placeholder="{{ __('shop/login.password') }}"></el-input>
          </el-form-item>
          @endhookwrapper

          @hook('account.login.password.after')

          {{-- 忘记密码 --}}
          <a class="text-muted forgotten-link" href="{{ shop_route('forgotten.index') }}">
            <i class="bi bi-question-circle"></i> {{ __('shop/login.forget_password') }}
          </a>

          <div class="mt-4 mb-3">
            <button type="button" @click="checkedBtnLogin('loginForm')" class="btn btn-dark btn-lg w-100 fw-bold">
              <i class="bi bi-box-arrow-in-right"></i> {{ __('shop/login.login') }}
            </button>
          </div>
        </div>
      </el-form>
    </div>

    {{-- 注册引导 --}}
    <div class="mt-4 text-center">
      <p>{{ __('shop/login.no_account') }}</p>
      <a href="{{ shop_route('register.index') }}" class="btn btn-outline-primary">{{ __('shop/login.register_now') }}</a>
    </div>
  </div>
</div>
@endsection

@push('add-scripts')
<script>
let app = new Vue({
  el: '#page-login',
  data: {
    loginForm: {
      email: '',
      password: '',
    },
    loginRules: {
      email: [
        { required: true, message: '{{ __('shop/login.enter_email') }}', trigger: 'change' },
        { type: 'email', message: '{{ __('shop/login.email_err') }}', trigger: 'change' },
      ],
      password: [
        { required: true, message: '{{ __('shop/login.enter_password')}}', trigger: 'change' }
      ],
    },
  },
  methods: {
    checkedBtnLogin(form) {
      this.$refs[form].validate((valid) => {
        if (!valid) return;

        $http.post('/login', this.loginForm).then((res) => {
          location.href = "{{ shop_route('account.index') }}";
        }).catch((err) => {
          this.$message.error(err.response.data.message || '{{ __('shop/login.error') }}');
        });
      });
    },
  },
});
</script>
@endpush
