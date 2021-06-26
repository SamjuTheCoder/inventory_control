@extends('layouts.guest')

@section('content')
<div class="col-lg-12">
    <div class="white_box mb_30">
        <div class="row justify-content-center">
          
            <div class="col-lg-5">
                <!-- sign_in  -->
                <div class="modal-content cs_modal">
                    <div class="modal-header justify-content-center theme_bg_1">
                        <h5 class="modal-title text_white">Log in</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" :value="old('email')" required autofocus class="form-control" placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert" style="display: block !important;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            
                            <div class="form-group">
                                <input type="password" name="password" required autocomplete="current-password" class="form-control" placeholder="Password">
                               @error('password')
                                    <span class="invalid-feedback" role="alert" style="display: block !important;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group cs_check_box">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="check_box" class="common_checkbox">
                                <label for="check_box">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            
                            
                            <button type="submit" class="btn_1 full_width text-center">Log in</button>
                            <!--<p>Need an account? 
                                @if (Route::has('register'))
                                <a data-toggle="modal" data-target="#sing_up" data-dismiss="modal"  href="{{ route('register') }}"> {{ __('Create Your Account') }}</a>
                                @endif
                            </p>-->
                            

                            @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a href="{{ route('password.request') }}" data-toggle="modal" data-target="#forgot_password" data-dismiss="modal" class="pass_forget_btn">{{ __('Forgot Your Password?') }}</a>
                                    </div>
                                @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
