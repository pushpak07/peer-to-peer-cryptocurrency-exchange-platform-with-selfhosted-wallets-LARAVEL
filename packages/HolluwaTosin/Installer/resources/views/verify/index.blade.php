@extends('installer::layouts.master')
@section('page', __('Welcome'))
@section('content')
	<form method="POST">
        @csrf
        <div class="wizard-header">
			<h3>
                <b>{{__('License Validation')}}</b> <br> <small>{{__('Something went wrong. :(')}}</small>
            </h3>
		</div>
		<div class="wizard-navigation">
			<ul class="steps">
				<li id="active_step"><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Verify Purchase')}}</a></li>
				<li><a href="#continue" data-toggle="tab">{{__('2.')}} {{__('Continue')}}</a></li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane" id="welcome">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						@include('installer::includes.alerts')
					</div>
					<div class="col-md-12">
						<h5 class="info-text">
							{{__("Hi there, we encountered some error which requires you to validate your license once again.")}}
						</h5>
					</div>

					<div class="col-sm-4 col-sm-offset-1" style="margin-top: 20px">
						<div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
							<div class="picture">
								<img src="{{config('installer.thumbnail')}}" class="picture-src"/>
							</div>
							<h6>
								<a href="{{config('installer.documentation')}}" style="color: black;">
									<i class="fa fa-file"></i> {{__('DOCUMENTATION')}}
								</a>
							</h6>
						</div>
					</div>

                    <div class="col-sm-6" style="margin-top: 20px">
                        <div class="form-group {{$errors->has('verification') ? ' has-error ' : ''}}">
                            <label class="control-label">
                                {{__('Purchase Code')}} <small>({{__('required')}})</small>
                            </label>

                            <input name="verification" type="text" class="form-control" required>
                        </div>
                    </div>
				</div>
			</div>
		</div>
		<div class="wizard-footer">
			<div class="pull-right">
				<input type="submit" class="btn btn-fill btn-success btn-wd" value="{{__('Verify')}}" />
			</div>
			<div class="clearfix"></div>
		</div>
	</form>
@endsection
@push('js')
	<script>
        var Page = function(){
            var handleValidation = function(){
                var form = $('.wizard-card form');
                var validator = form.validate({
                    rules: {
                        verification: {
                            required: true,
                            minlength: 10
                        }
                    },

                    errorPlacement: function(error, element) {
                        $(element).parent('div').addClass('has-error');
                    }
                });

                form.submit(function(e){
                    if(!$(this).valid()){
                        validator.focusInvalid();
                        return false;
                    }else{
                        return true
                    }
                });
            };

            return {
                init: function(){
                    handleValidation();
                }
            }
        }();

        $(document).ready(function(){
            Page.init()
        });
	</script>
@endpush
