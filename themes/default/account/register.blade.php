@extends('layout.master')

@section('body-class', 'page-register')

@push('header')
<script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
<script src="{{ asset('vendor/element-ui/index.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/element-ui/index.css') }}">
@endpush

@section('content')
<x-shop-breadcrumb type="static" value="register.index" />

<div class="container" id="page-register" v-cloak>
  <div class="hero-content pb-3 pb-lg-5 text-center">
    <h1 class="hero-heading">{{ __('shop/register.index') }}</h1>
  </div>

  <div class="register-wrap">
    <div class="card">
      <el-form ref="registerForm" :model="registerForm" :rules="registerRules" :inline-message="true">
        <div class="register-item-header card-header">
          <h6 class="text-uppercase mb-0">{{ __('shop/register.register') }}</h6>
        </div>
        <div class="card-body px-md-2">
          {{-- 注册表单 --}}
          @hookwrapper('account.register.email')
          <el-form-item label="{{ __('shop/register.email') }}" prop="email">
            <el-input @keyup.enter.native="checkedBtnRegister" v-model="registerForm.email" placeholder="{{ __('shop/register.email_address') }}"></el-input>
          </el-form-item>
          @endhookwrapper

          @hookwrapper('account.register.password')
          <el-form-item label="{{ __('shop/register.password') }}" prop="password">
            <el-input @keyup.enter.native="checkedBtnRegister" type="password" v-model="registerForm.password" placeholder="{{ __('shop/register.password') }}"></el-input>
          </el-form-item>
          @endhookwrapper

          @hookwrapper('account.register.invite_code')
          <el-form-item label="{{ __('shop/register.invite_code') }}" prop="invite_code">
            <el-input v-model="registerForm.invite_code" placeholder="{{ __('shop/register.optional_invite_code') }}"></el-input>
          </el-form-item>
          @endhookwrapper

          <div class="mt-5 mb-3">
            <button type="button" @click="checkedBtnRegister" class="btn btn-dark btn-lg w-100 fw-bold">
              <i class="bi bi-person"></i> {{ __('shop/register.register') }}
            </button>
          </div>
        </div>
      </el-form>
    </div>

    {{-- 登录引导 --}}
    <div class="mt-4 text-center">
      <p>{{ __('shop/register.already_have_account') }}</p>
      <a href="{{ shop_route('login.index') }}" class="btn btn-outline-primary">{{ __('shop/register.login_now') }}</a>
    </div>
  </div>
</div>
@endsection

@push('add-scripts')
<script>
let app = new Vue({
  el: '#page-register',
  data: {
    registerForm: {
      email: '',
      password: '',
      invite_code: '', // 新增邀请码字段
    },
    registerRules: {
      email: [
        { required: true, message: '{{ __('shop/register.enter_email') }}', trigger: 'change' },
        { type: 'email', message: '{{ __('shop/register.email_err') }}', trigger: 'change' },
      ],
      password: [
        { required: true, message: '{{ __('shop/register.enter_password')}}', trigger: 'change' }
      ],
    },
  },
  methods: {
    checkedBtnRegister() {
      this.$refs.registerForm.validate((valid) => {
        if (!valid) return;

        $http.post('/register', this.registerForm).then((res) => {
          location.href = "{{ shop_route('account.index') }}";
        }).catch((err) => {
          this.$message.error(err.response.data.message || '{{ __('shop/register.error') }}');
        });
      });
    },
  },
});
</script>
@endpush
