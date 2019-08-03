@extends('admin.layouts.master')
@section('page.name', __('Translation'))

@push('css')
	<style rel="stylesheet">
		.slide-fade-enter {
			transform: translateY(100px);
			opacity: 0;
		}
		
		.slide-fade-enter-active {
			transition: all .3s ease;
		}
		
		div.notification-list {
			height: 300px;
			position: relative;
		}
	</style>
@endpush

@section('page.body')
	<admin-platform-translation-page inline-template>
		<div class="content-wrapper">
			<div class="content-header row">
				<div class="content-header-left col-8 mb-2">
					<h3 class="content-header-title">
						{{__('Edit')}} {{ucfirst($group)}}
					</h3>
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							{{ Breadcrumbs::render('admin.platform.translation.group.edit', $group) }}
						</div>
					</div>
				</div>
				
				<div class="content-header-right col-4">
					<div class="btn-group float-right">
						<a class="btn btn-success box-shadow-2 px-2" data-ajax-type="POST"
						   href="{{route('admin.platform.translation.group.export', ['group' => $group])}}"
						   data-text="{{__("This will overwrite existing language files!")}}" data-swal="confirm-ajax">
							<i class="ft-flag"></i> {{__('Publish')}}
						</a>
					</div>
				</div>
			</div>
			
			<div class="content-body">
				<section class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">
									{{__('Edit Keys')}}
								</h4>
								<a class="heading-elements-toggle">
									<i class="la la-ellipsis-v font-medium-3"></i>
								</a>
								<div class="heading-elements">
									<p class="text-muted">
										{{__('Total')}}: {{$total}}, {{__('Changed')}}: {{$totalChanged}}
									</p>
								</div>
							</div>
							
							<div class="card-content">
								<div class="card-body">
									<div class="alert alert-info mb-2" role="alert">
										<strong>Heads up!</strong> Do not change the value of words that preceeds with
										the colon sign "<strong>:</strong>". They are replaced by specific parameters.
										e.g <strong>:name</strong> will be replaced with the actual name of the user.
									</div>
									
									<div class="table-responsive mb-2">
										<table class="table table-bordered mb-0">
											<thead>
											<tr>
												<th> {{__('Key')}} </th>
												
												@foreach($locales as $locale)
													<td> {{$locale}} </td>
												@endforeach
											</tr>
											</thead>
											<tbody>
											<tr v-for="(translation, index) in translation.data" :id="getKey(translation)">
												<td v-text="getKey(translation)"></td>
												
												<td v-for="locale in locales">
													<a href="#" @click.prevent="changeTranslation" data-title="{{__('Edit Translation')}}" :data-key="getKey(translation)"
													   data-text="{{__('Warning! Do not change words that precedes with the colon sign!')}}"
													   :data-value="getTranslation(translation, locale)" :data-index="index" :data-locale="locale">
														<i class="la la-edit"></i>
													</a>
													<span>
														@{{ getTranslation(translation, locale) }}
													</span>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
									
									<infinite-loading @infinite="translationsInfiniteHandler" ref="translationsInfiniteLoading">
										<h3 slot="no-more" class="text-center">
											{{__('No more translations available!')}}
										</h3>
									</infinite-loading>
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
			locales: {!! json_encode($locales) !!},
			translationGroup: '{{ $group }}',
		};
	</script>
@endpush