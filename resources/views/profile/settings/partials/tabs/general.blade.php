<div class="row">
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head">
				<div class="card-header">
					<h4 class="card-title"><i class="la la-user"></i> {{__('Profile Picture')}}</h4>
				</div>
			</div>
			
			<div class="card-content">
				<div class="card-body pb-3">
					<picture-input
							ref="picture-input"
							radius="50"
							@change="onPictureChange"
							@remove="onPictureRemove"
							width="320"
							height="320"
							@if(hasProfileAvatar($user))
							prefill="{{getProfileAvatar($user)}}"
							:removable="true"
							@endif
							:custom-strings="{
								selected: '{{__('Photo successfully selected!')}}',
								fileSize: '{{__('The file size exceeds the limit.')}}',
								fileType: '{{__('This file type is not supported.')}}',
								aspect: '<i class=\'la la-rotate-90\'></i>',
								change: '<i class=\'la la-pencil\'></i>',
								select: '<i class=\'la la-file-photo-o\'></i>',
								remove: '<i class=\'la la-trash\'></i>',
								upload: '{{__('Your device does not support file uploading.')}}',
								drag: '{{__('Drag an image or click here to select a file.')}}',
								tap: '{{__('Tap here to select a photo from your gallery.')}}'
							}">
					</picture-input>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head">
				<div class="card-header">
					<h4 class="card-title"><i class="la la-check-circle"></i> {{__('Verification')}}</h4>
				</div>
			</div>
			<div class="card-content">
				<div class="card-body">
					{!! Form::open(['url' => route('profile.settings.update-verification', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
					<div class="form-body">
						<div class="form-group">
							<p class="card-text">
								<b>{{__('PHONE')}}</b>
								
								<a href="#" @click.prevent="profile.options.edit_phone = true"
								   class="float-right primary" v-if="!profile.options.edit_phone">
									<i class="la la-pencil"></i>
								</a>
							</p>
							<fieldset>
								{!! Form::tel('phone', $user->phone, ['class' => 'form-control', 'v-model' => 'profile.phone', 'id' => 'phone', ':disabled' => '!profile.options.edit_phone']) !!}
								{!! Form::hidden('phone_country', null, ['id' => 'phone-country']) !!}
								
								<div v-if="profile.verification.phone && !profile.options.edit_phone">
									<p class="text-left block-tag text-muted">
										<span>{{__('Enter the code you receive via SMS')}}</span>
										
										<a href="{{route('ajax.profile.resendVerificationSms', ['user' => $user->name])}}" data-swal="confirm-ajax"
										   data-ajax-type="POST" data-text="{{__("A new verification sms will be sent to you.")}}">
											{{__('Resend?')}}
										</a>
									</p>
									
									<div class="input-group">
										{!! Form::text('phone_code', null, ['class' => 'form-control round', 'placeholder' => __('Enter confirmation code.'), 'v-model' => 'form.phone_code']) !!}
										
										<div class="input-group-append">
											<button class="btn btn-secondary" type="button" data-swal="confirm-ajax"
											        data-link="{{route('ajax.profile.confirmPhone', ['user' => $user->name])}}"
											        data-ajax-type="POST" :data-ajax-data='getPhoneCode()'>
												{{__('Confirm')}}
											</button>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						
						<div class="form-group">
							<p class="card-text">
								<b>{{__('EMAIL')}}</b>
								
								<a href="#" @click.prevent="profile.options.edit_email = true"
								   class="float-right primary" v-if="!profile.options.edit_email">
									<i class="la la-pencil"></i>
								</a>
							</p>
							<fieldset>
								{!! Form::email('email', $user->email, ['class' => 'form-control', 'v-model' => 'profile.email', ':disabled' => '!profile.options.edit_email']) !!}
								
								<p class="text-left block-tag text-muted" v-if="profile.verification.email && !profile.options.edit_email">
									<span>{{__('Please follow the link provided in the verification email!')}}</span>
									
									<a href="{{route('ajax.profile.resendVerificationEmail', ['user' => $user->name])}}" data-swal="confirm-ajax"
									   data-ajax-type="POST" data-text="{{__("A new verification email will be sent to you.")}}">
										{{__('Resend?')}}
									</a>
								</p>
							</fieldset>
						</div>
						
						<div class="form-group row" v-if="profile.options.edit_email || profile.options.edit_phone">
							<div class="col">
								{{__('Verify your Identity:')}}
							</div>
							<div class="col">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="ft-lock"></i></span>
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
	</div>
</div>


<div class="row">
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head ">
				<div class="card-header">
					<h4 class="card-title"><i class="la la-list-ul"></i> {{__('Account Preferences')}}</h4>
				</div>
			</div>
			<div class="card-content">
				<div class="card-body">
					{!! Form::open(['url' => route('profile.settings.update-preferences', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
					<div class="form-body">
						<h4 class="form-section">
							<i class="ft-bell"></i> {{__('Notifications')}}
						</h4>
						
						<div class="row">
							<label class="col-md-5 d-none d-md-inline">
								<b>{{__('TYPE')}}</b>
							</label>
							
							<div class="col-md-7">
								<div class="row">
									<div class="text-center col"><i class="ft-bell"></i></div>
									<div class="text-center col"><i class="ft-mail"></i></div>
									<div class="text-center col"><i class="ft-smartphone"></i></div>
								</div>
							</div>
						</div>
						
						@foreach($notification_settings as $notification)
							<div class="form-group row">
								<label class="col-md-5">
									{{$notification->description}}
								</label>
								
								<div class="col-md-7">
									<div class="row">
										<div class="text-center col">
											@if($notification->database !== null)
												{!! Form::checkbox("notification[{$notification->name}][database]", 1, $notification->database) !!}
											@endif
										</div>
										
										<div class="text-center col">
											@if($notification->email !== null)
												{!! Form::checkbox("notification[{$notification->name}][email]", 1, $notification->email) !!}
											@endif
										</div>
										
										<div class="text-center col">
											@if($notification->sms !== null)
												{!! Form::checkbox("notification[{$notification->name}][sms]", 1, $notification->sms) !!}
											@endif
										</div>
									</div>
								</div>
							</div>
						@endforeach
						
						<h4 class="form-section">
							<i class="ft-settings"></i> {{__('Others')}}
						</h4>
						
						<div class="form-group row">
							{{Form::label('timezone', __('Select Timezone'), ['class' => 'col-xl-3'])}}
							
							<div class="col-xs-12 col-xl-9">
								{{Form::select('timezone', get_php_timezones(), $user->timezone, ['is' => 'select2', 'html-class' => 'form-control', 'placeholder' => __('Select timezone')])}}
							</div>
						</div>
						
						<div class="form-group row">
							{!! Form::label('currency', __('Select Currency'), ['class' => 'col-xl-3']) !!}
							
							<div class="col-xs-12 col-xl-9">
								{{Form::select('currency', get_iso_currencies(), $user->currency, ['is' => 'select2', 'html-class' => 'form-control', 'placeholder' => __('Select currency'), 'v-model' => 'profile.currency'])}}
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
	</div>
	
	<div class="col-xl-6">
		<div class="card">
			<div class="card-head ">
				<div class="card-header">
					<h4 class="card-title"><i class="la la-list"></i> {{__('Profile Details')}}</h4>
				</div>
			</div>
			<div class="card-content">
				<div class="card-body">
					{!! Form::open(['url' => route('profile.settings.update-profile', ['user' => $user->name]), 'method' => 'POST', 'data-ajax']) !!}
					<div class="form-body">
						<h4 class="form-section">
							<i class="ft-user"></i> {{__('Personal Info')}}
						</h4>
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									{!! Form::label('first_name', __('First Name')) !!}
									{!! Form::text('first_name', $profile->first_name, ['class' => 'form-control']) !!}
								</div>
								
								<div class="form-group">
									{!! Form::label('last_name', __('Last Name')) !!}
									{!! Form::text('last_name', $profile->last_name, ['class' => 'form-control']) !!}
								</div>
								
								<div class="form-group">
									{!! Form::label('bio', __('Bio')) !!}
									{!! Form::textarea('bio', $profile->bio, ['class' => 'form-control', 'rows' => '4']) !!}
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
	</div>
	
</div>


<div id="cropper-custom" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="cropper-content">
			<div class="modal-header bg-primary white">
				<span>{{__('Crop and Upload Photo')}}</span>
			</div>
			<div class="modal-body image-container" style="text-align:center;"></div>
			<div class="modal-footer">
				<button class="btn btn-primary crop-upload" type="submit">
					{{__('Upload')}}
				</button>
			</div>
		</div>
	</div>
</div>


@push('scripts')
	<script type="text/javascript">
		window._pictureUploadUrl = "{{route('profile.settings.upload-picture', ['user' => $user->name])}}";
	</script>
@endpush