@extends('auth.auth_layouts.auth')

@section('auth_content')

    <!-- Login  area Start-->
<form method="POST" action="{{ route('reset_password_without_token') }}">
    @csrf
    <div class="login-content">
        <!-- Login -->
        <div class="nk-block toggled" id="l-login">
            <h3 style="color: white">Reset Password</h3>

            <div class="nk-form">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                    <div class="nk-int-st">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus placeholder="Enter Your Old Email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
{{--                    <button type="submit"  style="top: 72%;"  class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow right-arrow-ant"></i>Submit</button>--}}
                    <button type="submit" class="btn btn-success waves-effect">Submit</button>

                </div>
            </div>
        </div>

    </div>
</form>

@endsection
