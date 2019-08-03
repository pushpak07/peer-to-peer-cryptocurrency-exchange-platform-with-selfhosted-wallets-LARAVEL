@extends('admin.layouts.master')
@section('page.name', __('Translation'))
@section('page.body')
	<admin-platform-translation-page inline-template>
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-8 mb-2">
					<h3 class="content-header-title">
						{{__('Translation')}}
					</h3>
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							{{ Breadcrumbs::render('admin.platform.translation') }}
						</div>
					</div>
				</div>
				
				<div class="content-header-right col-4">
					<div class="btn-group float-right">
						<a class="btn btn-success box-shadow-2 px-2" data-ajax-type="POST"
						   href="{{route('admin.platform.translation.export')}}"
						   data-text="{{__("This will overwrite existing language files!")}}" data-swal="confirm-ajax">
							<i class="ft-flag"></i> {{__('Publish All')}}
						</a>
					</div>
				</div>
			</div>
			
			<div class="alert alert-info alert-icon-left mb-2" role="alert">
				<span class="alert-icon"><i class="la la-info"></i></span>
				Publishing all changes made to translation keys may take a while
				to complete. Please consider using SSH command:
				<code>php artisan translations:export</code>. Make sure the
				<code>resource/lang</code> folder is granted write permission, i.e
				<strong>777</strong>, and a full backup is made on the folder
				before this action is taking.
			</div>
			
			<div class="alert alert-warning alert-icon-left mb-2" role="alert">
				<span class="alert-icon"><i class="la la-warning"></i></span>
				Importing all translation keys may take a while to complete. Please consider
				using SSH command: <code>php artisan translations:import</code>. You may add
				the
				option <code>--replace</code> to replace all values.
			</div>
			
			<div class="content-body">
				<section class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">
									{{__('Locale Setup')}}
								</h4>
							</div>
							
							<div class="card-content">
								<div class="card-body">
									<div class="form-body">
										
										<h4 class="card-title">
											<i class="ft-download"></i> {{__('Import Translation')}}
										</h4>
										
										<div class="form-group row">
											
											<div class="col-md-8">
												<select is="select2" name="type" class="form-control" v-model="importType" required>
													<option value="0">{{__('Append new translations')}}</option>
													<option value="1">{{__('Replace existing translations')}}</option>
												</select>
											</div>
											
											<div class="col-md-4 mt-md-0 mt-1">
												<div class="row">
													<div class="col-6">
														<a href="{{route('admin.platform.translation.import-translation')}}"
														   data-swal="confirm-ajax" :data-ajax-data='getImportType()' class="btn btn-block btn-warning" data-ajax-type="POST"
														   data-text="{{__("This may take a while!")}}">
															<i class="ft-download"></i> {{__('Import')}}
														</a>
													</div>
													
													
													<div class="col-6">
														<a href="{{route('admin.platform.translation.find-translation')}}"
														   data-swal="confirm-ajax" class="btn btn-block btn-info" data-ajax-type="POST"
														   data-text="{{__("This may take a while!")}}">
															<i class="ft-search"></i>  {{__('Find in Files')}}
														</a>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group row">
											<div class="col-md-8">
												{!! Form::select('group', $groups, null, ['is' => 'select2', 'class' => 'form-control', 'placeholder' => __('Choose a group.'), 'v-model' => 'translationGroup']) !!}
											</div>
											
											<div class="col-md-4 mt-md-0 mt-1">
												<a :href="getTranslationLink()" class="btn btn-block btn-secondary">
													<i class="ft-edit"></i> {{__('Edit Keys')}}
												</a>
											</div>
										</div>
										
										<h4 class="card-title mt-2"><i class="ft-flag"></i> {{__('Supported Locales')}}
										</h4>
										
										<div class="table-responsive mb-2">
											<table class="table table-bordered mb-0">
												<thead>
												<tr>
													<th>{{__('Locale')}}</th>
													<th>{{__('Name')}}</th>
													<th>{{__('Native')}}</th>
													<th></th>
												</tr>
												</thead>
												<tbody>
												@foreach($locales as $key => $locale)
													<tr>
														<th> {{$key}} </th>
														<td> {{$locale['name']}} </td>
														<td> {{$locale['native']}} </td>
														<td>
															@if($key != 'en')
																<a href="{{route('admin.platform.translation.remove-locale')}}" class="danger"
																   data-swal="confirm-ajax" data-ajax-type="POST" data-ajax-data='{"locale": "{{$key}}"}'
																   data-text="{{__("Be careful! This cannot be undone.")}}">
																	<i class="la la-remove"></i>
																</a>
															@endif
														</td>
													</tr>
												@endforeach
												
												@if(!count($locales))
													<tr>
														<td colspan="3" class="text-center">
															{{__('No supported locale found yet! Import new translations to begin.')}}
														</td>
													</tr>
												@endif
												
												</tbody>
											</table>
										</div>
										
										<h4 class="card-title mt-2"><i class="ft-settings"></i> {{__('Update Settings')}}
										</h4>
										
										{!! Form::open(['url' => route('admin.platform.translation.add-locale'), 'class' => 'form']) !!}
										<div class="form-group row mt-2">
											<div class="col-md-8">
												{!! Form::text('locale', null, ['placeholder' => 'Enter language locale.', 'class' => 'form-control']) !!}
											</div>
											
											<div class="col-md-4 mt-md-0 mt-1">
												<div class="row">
													<div class="col-6">
														<button type="submit" class="btn btn-info btn-block">
															<i class="ft-plus"></i> {{__('Add Locale')}}
														</button>
													</div>
												</div>
											</div>
										</div>
										{!! Form::close() !!}
										
										{!! Form::open(['class' => 'form', 'method' => 'POST']) !!}
										<div class="form-group row mt-2">
											<div class="col-md-8">
												{!! Form::select('APP_LOCALE', getAvailableLocales(), env('APP_LOCALE'), ['is' => 'select2', 'class' => 'form-control', 'required']) !!}
											</div>
											
											<div class="col-md-4 mt-md-0 mt-1">
												<div class="row">
													<div class="col-6">
														<button type="submit" class="btn btn-block btn-success">
															<i class="ft-save"></i> {{__('Update')}}
														</button>
													</div>
												</div>
											</div>
										</div>
										{!! Form::close() !!}
										
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</admin-platform-translation-page>
@endsection

@push('data')
	<script type="text/javascript">
		window._vueData = {
			translationGroup: '',
		};
	</script>
@endpush