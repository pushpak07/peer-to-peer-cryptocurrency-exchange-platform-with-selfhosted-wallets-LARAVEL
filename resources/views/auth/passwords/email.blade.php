@extends('auth.layouts.master')
@section('page.name', __('Reset Password'))
@section('page.body')
    <div class="container-login">
        <div class="wrap-login">
            {!! Form::open(['route' => 'password.email', 'method' => 'POST', 'class' => 'login-form',  'id' => 'auth-form', 'novalidate' => true]) !!}
            
            <div class="login-form-title mb-3">
                <div class="text-center pb-1">
                    <a href="{{url('/')}}">
                        <img src="{{config('app.logo_brand') ?: asset('/images/logo/logo-dark.png')}}" alt="logo">
                    </a>
                </div>
                
                {{__('Reset Password')}}
            </div>
            
            @include('auth.includes.alerts')
            
            
            <fieldset class="form-group my-2 position-relative has-icon-left {{ $errors->has('email') ? 'error' : '' }}">
                {!! Form::email('email', null, ['id' => 'email', 'class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Email Address')]) !!}
                <div class="form-control-position"><i class="ft-mail font-medium-4"></i></div>
            </fieldset>
            
            @include('auth.includes.nocaptcha', [
				'button' => [
					'title'         => '<i class="ft-user"></i> '. __('Send Password Reset Link'),
					'attributes'    => ['class' => 'btn '. platformSettings()->template()->button(). ' btn-glow btn-lg mt-3 btn-block ladda-button', 'type' => 'submit']
				]
			])
            
            <div class="text-center my-2">
                <a href="{{route('login')}}" class="txt2">
                    {{__('Already have an account?')}}
                </a>
            </div>
            
            {!! Form::close() !!}
            
            <div class="login-more" style="background-image: url('{{asset('images/backgrounds/auth.jpg')}}');"></div>
        </div>
    </div>
@endsection
