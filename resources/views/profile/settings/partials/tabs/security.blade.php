<div class="card">
	<div class="card-head ">
		<div class="card-header">
			<h4 class="card-title">{{__('TWO FACTOR SETTINGS')}}</h4>
		</div>
	</div>
	<div class="card-content">
		<div class="card-body">
			{!! Form::open(['url' => route('profile.settings.update-settings', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
			<div class="form-body">
				<div class="row">
					<div class="col-md-6">
						<h4 class="form-section">
							<i class="ft-user"></i> {{__('User Login')}}
						</h4>
						<div class="form-group">
							<fieldset>
								<div class="custom-control custom-radio">
									{!! Form::radio('user_login_2fa', 0, $setting->user_login_2fa == false, ['class' => 'custom-control-input']) !!}
									{!! Form::label('user_login_2fa', __('NONE'), ['class' => 'custom-control-label']) !!}
								</div>
							</fieldset>
							
							<fieldset class="py-1">
								<div class="custom-control custom-radio">
									{!! Form::radio('user_login_2fa', 1, $setting->user_login_2fa == true, ['class' => 'custom-control-input', 'disabled' => !$setting->google2fa_status]) !!}
									{!! Form::label('user_login_2fa', 'GOOGLE AUTHENTICATOR', ['class' => 'custom-control-label']) !!}
								</div>
							</fieldset>
						</div>
					</div>
					<div class="col-md-6">
						<h4 class="form-section">
							<i class="ft-fast-forward"></i> {{__('Outgoing Transfer')}}
						</h4>
						
						<div class="form-group">
							<fieldset>
								<div class="custom-control custom-radio">
									{!! Form::radio('outgoing_transfer_2fa', 0, $setting->outgoing_transfer_2fa == false, ['class' => 'custom-control-input']) !!}
									{!! Form::label('outgoing_transfer_2fa', __('NONE'), ['class' => 'custom-control-label']) !!}
								</div>
							</fieldset>
							
							<fieldset class="py-1">
								<div class="custom-control custom-radio">
									{!! Form::radio('outgoing_transfer_2fa', 1, $setting->outgoing_transfer_2fa == true, ['class' => 'custom-control-input', 'disabled' => !$setting->google2fa_status]) !!}
									{!! Form::label('outgoing_transfer_2fa', 'GOOGLE AUTHENTICATOR', ['class' => 'custom-control-label']) !!}
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				
				<div class="row" v-if="!profile.settings.google2fa_status">
					<div class="col-sm-6 py-1 text-center">
						{!! HTML::image($qr_code, null, ['class' => 'img-thumbnail']) !!}
					</div>
					<div class="col-sm-6 py-1">
						<div class="card-text">
							{{__('Scan the QR code with Google Authenticator or Auth Mobile App, or enter the code.')}}
						</div>
						<div class="card-text pt-1">
							{{__('SECRET')}}: <b>{{$user->google2fa_secret}}</b>
						</div>
						<div class="card-text pt-1">
							{{__('Enter the code shown in the authenticator app below:')}}
						</div>
						
						<div class="card-text col-md-8 offset-md-2 pt-1">
							<div class="input-group">
								{{Form::text('twofa_code', null, ['class' => 'form-control', 'placeholder' => 'e.g 234561', 'v-model' => 'form.twofa_code'])}}
								
								<div class="input-group-append">
									<button class="btn btn-secondary" type="button" data-swal="confirm-ajax"
									        data-link="{{route('profile.2fa.setup', ['user' => $user->name])}}"
									        data-ajax-type="POST" :data-ajax-data='getTwofaCode()'>
										{{__('Confirm')}}
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group row right pt-1">
					<label class="col-md-6">{{__('Enter password')}}</label>
					
					<div class="col-md-6">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="ft-lock"></i></span>
							</div>
							
							{{Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Password')])}}
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-actions right">
				<button type="submit" class="btn btn-success ladda-button">
					<i class="la la-save"></i> {{__('Save')}}
				</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head">
				<div class="card-header">
					<h4 class="card-title">
						<i class="ft-lock"></i> {{__('Change Password')}}
					</h4>
				</div>
			</div>
			<div class="card-content">
				<div class="card-body">
					{!! Form::open(['url' => route('profile.settings.update-password', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
					<div class="form-body min">
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i class="ft-lock"></i>
									</span>
								</div>
								
								{{Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Password')])}}
							</div>
						</div>
						
						<div class="form-group border-top-blue-grey border-top-lighten-3 pt-2">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-secondary white" id="basic-addon1">
										<i class="ft-lock"></i></span>
								</div>
								
								{{Form::password('password', ['class' => 'form-control', 'placeholder' => __('New Password')])}}
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-secondary white" id="basic-addon1">
										<i class="ft-lock"></i></span>
								</div>
								
								{{Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('Confirm Password'), 'data-validation-matches-match' => 'password'])}}
							</div>
						</div>
					
					</div>
					
					<div class="form-actions right">
						<button type="submit" class="btn btn-warning ladda-button">
							{{__('UPDATE PASSWORD')}}
						</button>
					</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head ">
				<div class="card-header">
					<h4 class="card-title">
						<i class="ft-trash"></i> {{__('Delete Account')}}
					</h4>
				</div>
			</div>
			<div class="card-content">
				<div class="card-body">
					{!! Form::open(['url' => route('profile.settings.delete-account', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
					<div class="form-body">
						<div class="alert alert-danger mb-3" role="alert">
							<b>{{__('Note:')}}</b> {{trans('auth.delete.warning_message')}}
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1"><i class="ft-lock"></i></span>
								</div>
								
								{{Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Password')])}}
							</div>
						</div>
						
						
					</div>
					
					<div class="form-actions right">
						<button type="submit" class="btn btn-danger ladda-button">
							{{__('DELETE ACCOUNT')}}
						</button>
					</div>
					
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
