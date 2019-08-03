@extends('layouts.master')
@section('page.name', __(":name - Settings", ['name' => $user->name]))
@push('css')
	<link rel="stylesheet" type="text/css" href="{{asset('css/pages/profile.css')}}">
@endpush
@section('page.body')
	<profile-settings-page inline-template>
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-md-6 col-12 mb-2">
					<h3 class="content-header-title">
						{{strtoupper($user->name) . ' | ' . __('Settings')}}
					</h3>
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							{{ Breadcrumbs::render('profile', $user->name) }}
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="content-detached content-right">
				<div class="content-body">
					<section class="row">
						<div class="col-12">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane fade active in show" id="general"
								     aria-labelledby="general-tab" aria-expanded="true">
									@include('profile.settings.partials.tabs.general')
								</div>
								<div class="tab-pane fade" id="security" role="tabpanel"
								     aria-labelledby="security-tab" aria-expanded="false">
									@include('profile.settings.partials.tabs.security')
								</div>
								
								@if(Auth::user()->canManage($user))
									<div class="tab-pane fade" id="panel" role="tabpanel"
									     aria-labelledby="panel-tab" aria-expanded="false">
										@include('profile.settings.partials.tabs.administration')
									</div>
								@endif
							</div>
						</div>
					</section>
				</div>
			</div>
			
			<div class="sidebar-detached sidebar-left sidebar-sticky">
				<div class="sidebar">
					<div class="sidebar-content card">
						<!-- Predefined Views -->
						<div class="card-head">
							<div class="media p-1">
								<div class="media-left pr-1">
									<span class="avatar avatar-sm {{getPresenceClass($user)}} rounded-circle">
										<img src="{{getProfileAvatar($user)}}" alt="avatar"><i></i>
									</span>
								</div>
								<div class="media-body media-middle">
									<h5 class="media-heading">{{$user->name}}</h5>
								</div>
							</div>
						</div>
						
						<div class="card-body border-top-blue-grey border-top-lighten-5">
							<ul class="nav nav-pills nav-pill-with-active-bordered flex-column">
								<li class="nav-item">
									<a class="nav-link active" id="general-tab" data-toggle="pill"
									   href="#general" role="tab" aria-controls="general" aria-expanded="true">
										<i class="ft-globe"></i> {{__('General')}}
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="security-tab" data-toggle="pill"
									   href="#security" role="tab" aria-controls="security" aria-expanded="false">
										<i class="ft-shield"></i> {{__('Security')}}
									</a>
								</li>
								
								@if(Auth::user()->priority() < $user->priority())
									<li class="nav-item">
										<a class="nav-link" id="panel-tab" data-toggle="pill"
										   href="#panel" role="tab" aria-controls="panel" aria-expanded="false">
											<i class="ft-briefcase"></i> {{__('Manage')}}
										</a>
									</li>
								@endif
							
							</ul>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</profile-settings-page>
@endsection

@push('data')
	<script type="text/javascript">
		window._tableData = [
			{
				'selector': '#moderation-activities',
				'options': {
					processing: false,
					serverSide: true,

					"ajax": {
						"async": true,
						"type": "POST",
						"url": '{{route('profile.settings.moderation-activity-data', ['user' => $user->name])}}'
					},

					searching: false,

					columns: [
						{data: null, defaultContent: ''},
						{data: 'moderator'},
						{data: 'activity'},
						{data: 'comment', orderable: false},
						{data: 'created_at', searchable: false}
					],

					"order": [
						[4, 'desc']
					],
				}
			}
		];

		window._vueData = {!! json_encode([
                'profile' => [
                    'name' => $user->name,
                    'last_seen' => $user->last_seen,
                    'presence' => $user->presence,
                    'id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'currency' => $user->currency,

                    'verification' => [
                        'email' => !boolval($user->verified) && !empty($user->email),
                        'phone' => !boolval($user->verified_phone) && !empty($user->phone),
                    ],

                    'settings' => [
                        'google2fa_status' => boolval($setting->google2fa_status)
                    ],

                    'options' => [
                        'edit_email' => !$user->email,
                        'edit_phone' => !$user->phone
                    ],

                    'roles' => $user->getRoleNames()
                ],

                'form' => [
                    'twofa_code' => '',
                    'phone_code' => '',
                ]
            ]) !!}
	</script>
@endpush
