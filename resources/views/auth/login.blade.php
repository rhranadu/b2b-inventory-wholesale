@extends('auth.auth_layouts.auth')

@section('auth_content')
    <div class="login-signin">
        <div class="mb-20">
            <h3 class="">Sign In To Inventory</h3>
            <p class="opacity-60">Enter your details to login to your account:</p>
        </div>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                <div class="nk-int-st">
                    <input type="email" name="email"
                           class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5"
                           placeholder="email">
                    @error('email')
                    <strong class="text-danger" role="alert">
                        <span>{{ $message }}</span>
                    </strong>
                    @enderror
                </div>
            </div>


            <div class="form-group">
                <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                <div class="nk-int-st">
                    <input type="password" name="password"
                           class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8 mb-5"
                           placeholder="Password">
                    @error('password')
                    <strong class="text-danger" role="alert">
                        <span>{{ $message }}</span>
                    </strong>
                    @enderror
                </div>
            </div>


            <div class="form-group d-flex flex-wrap justify-content-between align-items-center px-8">
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-outline checkbox-white text-white m-0">
                        <input type="checkbox" name="remember"/>
                        <span></span>Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-white font-weight-bold"
                       data-ma-action="nk-login-switch" data-ma-block="#l-forget-password">Forget Password ?</a>
                @endif
            </div>
            <div class="form-group text-center mt-10">
                <button id="kt_login_signin_submit" class="btn btn-pill btn-primary opacity-90 px-15 py-3">Sign In
                </button>
            </div>
        </form>
{{--        <div class="mt-10">--}}
{{--            <span class="opacity-70 mr-4">Don't have an account yet?</span>--}}
{{--            <a href="javascript:void(0)" id="kt_login_signup" class="text-white font-weight-normal">Sign--}}
{{--                Up</a>--}}
{{--        </div>--}}
    </div>

@endsection

