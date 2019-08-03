@extends('auth.layouts.master')
@section('page.name', __('Reset Password'))
@section('page.body')
    <div class="container-login">
        <div class="wrap-login">
            {!! Form::open(['route' => 'password.request', 'method' => 'POST', 'class' => 'login-form form-horizontal',  'id' => 'auth-form', 'novalidate' => true]) !!}
    
            <input type="hidden" name="token" value="{{ $token }}">
            
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
                {!! Form::email('email', null, ['id' => 'email', 'class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Email')]) !!}
                <div class="form-control-position"><i class="ft-mail font-medium-4"></i></div>
            </fieldset>
            
            <fieldset class="form-group my-2 position-relative has-icon-left {{ $errors->has('password') ? 'error' : '' }}">
                {!! Form::password('password', ['class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Password')]) !!}
                <div class="form-control-position"><i class="ft-lock font-medium-4"></i></div>
            </fieldset>
            
            <fieldset class="form-group my-2 position-relative has-icon-left">
                {!! Form::password('password_confirmation', ['class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Verify Password')]) !!}
                <div class="form-control-position"><i class="ft-lock font-medium-4"></i></div>
            </fieldset>
            
            @include('auth.includes.nocaptcha', [
				'button' => [
					'title'         => '<i class="ft-user"></i> '. __('Reset Password'),
					'attributes'    => ['class' => 'btn '. platformSettings()->template()->button(). ' btn-glow btn-lg mt-3 btn-block ladda-button', 'type' => 'submit']
				]
			])
            
            {!! Form::close() !!}
            
            <div class="login-more" style="background-image: url('{{asset('images/backgrounds/auth.jpg')}}');"></div>
        </div>
    </div>
@endsection