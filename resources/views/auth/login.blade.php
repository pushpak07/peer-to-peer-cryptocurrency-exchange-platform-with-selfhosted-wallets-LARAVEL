@extends('auth.layouts.master')
@section('page.name', __('Login'))
@section('page.body')
	<div class="container-login">
		<div class="wrap-login">
			{!! Form::open(['route' => 'login', 'method' => 'POST', 'class' => 'login-form form-horizontal',  'id' => 'auth-form', 'novalidate' => true]) !!}
			
			<div class="login-form-title mb-3">
				<div class="text-center pb-1">
					<a href="{{url('/')}}">
						<img src="{{config('app.logo_brand') ?: asset('/images/logo/logo-dark.png')}}" alt="branding logo">
					</a>
				</div>
				
				{{__('Login to continue!')}}
			</div>
			
			@include('auth.includes.alerts')
			
			<fieldset class="form-group my-2 position-relative has-icon-left {{ $errors->has('name') ? 'error' : '' }}">
				{!! Form::text('name', null, ['id' => 'name', 'class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Username')]) !!}
				<div class="form-control-position"><i class="ft-user font-medium-4"></i></div>
			</fieldset>
			
			
			<fieldset class="form-group my-2 position-relative has-icon-left {{ $errors->has('password') ? 'error' : '' }}">
				{!! Form::password('password', ['class' => 'input-xl font-small-3 form-control', 'required' => true, 'placeholder' => __('Password'),]) !!}
				<div class="form-control-position"><i class="ft-lock font-medium-4"></i></div>
			</fieldset>
			
			@if($errors->has('token'))
				<p class="card-subtitle mt-3 line-on-side text-muted text-center font-small-3 mx-2">
					<span>{{__('Verify your Identity')}}</span>
				</p>
				
				<fieldset class="form-group my-2 position-relative has-icon-left">
					{!! Form::text('token', null, ['class' => 'input-xl font-small-3 form-control', 'placeholder' => __('2FA Token'), 'required' => true]) !!}
					<div class="form-control-position"><i class="ft-unlock font-medium-4"></i></div>
				</fieldset>
			@endif
			
			<div class="form-group row mt-3 mb-2">
				<div class="col text-left">
					<fieldset>
						<div class="custom-control custom-checkbox">
							{!! Form::checkbox('remember', 1, null, ['class' => 'custom-control-input', 'id' => 'remember']) !!}
							<label class="custom-control-label" for="remember">{{__('Remember Me')}}</label>
						</div>
					</fieldset>
				</div>
				
				<div class="col text-right">
					<a href="{{route('password.request')}}" class="card-link">{{__('Forgot Password?')}}</a>
				</div>
			</div>
			
			@include('auth.includes.nocaptcha', [
				'button' => [
					'title'         => '<i class="ft-unlock"></i> '. __('Login'),
					'attributes'    => ['class' => 'btn '. platformSettings()->template()->button(). ' btn-glow btn-lg btn-block ladda-button', 'type' => 'submit']
				]
			])
			
			<div class="text-center my-2">
				<a href="{{route('register')}}" class="txt2">
					{{__('New to :name?', ['name' => config('app.name')])}}
				</a>
			</div>
			
			{!! Form::close() !!}
			
			<div class="login-more" style="background-image: url('{{asset('images/backgrounds/auth.jpg')}}');"></div>
		</div>
	</div>
@endsection

@if($errors->has('token'))
	@push('scripts')
		<script> document.getElementById('two-factor').style.display = 'block'; </script>
	@endpush
@endif