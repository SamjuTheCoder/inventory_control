@extends('layouts.guest')

@section('content')


<div class="col-lg-12">
    <div class="white_box mb_30">
        <div class="row justify-content-center">
          
            <div class="col-lg-5">
                <!-- sign_in  -->
                <div class="modal-content cs_modal">
                    <div class="modal-header justify-content-center theme_bg_1">
                        <h5 class="modal-title text_white">Register</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus  placeholder="Enter your full name">
    
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>


                            <div class="form-group">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email" >
                                @error('email')
                                    <span class="invalid-feedback" role="alert" style="display: block !important;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            
                            <div class="form-group">
                                <input type="password" name="password" required autocomplete="current-password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                               @error('password')
                                    <span class="invalid-feedback" role="alert" style="display: block !important;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                            </div>
                            
                            <button type="submit" class="btn_1 full_width text-center">{{ __('Sign Up') }}</button>
                            <p>Already have an existing account? 
                                @if (Route::has('register'))
                                <a data-toggle="modal" data-target="#sing_up" data-dismiss="modal"  href="{{ route('login') }}"> {{ __('Log in') }}</a>
                                @endif
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
