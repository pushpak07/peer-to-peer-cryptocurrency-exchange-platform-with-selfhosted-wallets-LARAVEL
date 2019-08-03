@extends('installer::layouts.master')
@section('page', __('Finished'))
@push('css')
	<style>
		.gold-label{
			color: #FDB931;
		}
	</style>
@endpush
@section('content')
	<div class="wizard-header">
        <h3>
            <b>{{__('Installation Wizard')}} </b> <br/>
            <small>{{__("Final step!.")}}</small>
        </h3>
	</div>
	<div class="wizard-navigation">
		<ul class="steps">
			<li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
			<li><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
			<li><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
			<li><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a></li>
			<li id="active_step"><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane" id="finish">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					@include('installer::includes.alerts')
				</div>
				<div class="col-md-12">
					<h5 class="info-text">
						 {{__("By clicking the start button, you agree to all our terms of service...")}}
					</h5>
				</div>
				<div class="col-md-12">
					<div class="col-sm-4 col-sm-offset-1" style="margin-top: 20px">
						<div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
							<div class="picture">
								<img src="{{config('installer.thumbnail')}}" class="picture-src"/>
							</div>
							<h6>
								<a href="{{config('installer.link')}}" target="_blank">
									<i class="fa fa-star gold-label"></i>
									<i class="fa fa-star gold-label"></i>
									<i class="fa fa-star gold-label"></i>
									<i class="fa fa-star gold-label"></i>
									<i class="fa fa-star gold-label"></i>
								</a>
							</h6>
						</div>
					</div>
					<div class="col-sm-6">
						<blockquote>
                            A <b>five star rating</b> will be very much appreciated :), it keeps us motivated to release more updates...
                            If you have any need to contact us, make use of our support extension on the sales page. <b>Thank you!</b>
                            <footer class="blockquote-footer text-right"> Developer
                                <cite title="{{config('installer.author.name')}}">
                                    ({{config('installer.author.name')}})
                                </cite>
                            </footer>
                        </blockquote>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="wizard-footer">
		<div class="pull-right">
            <a href="{{ route('Installer::overview.finish') }}" class="btn btn-fill btn-success btn-wd"
               onclick="event.preventDefault(); document.getElementById('start-form').submit();">
                {{ __('Start') }}
            </a>

            <form id="start-form" action="{{route('Installer::overview.finish')}}" method="POST" style="display: none;">
                @csrf
            </form>
		</div>
		<div class="clearfix"></div>
	</div>
@endsection
@push('js')
	<script>
        var Page = function(){

            return {
                init: function(){

                }
            }
        }();

        $(document).ready(function(){
            Page.init()
        });
	</script>
@endpush
