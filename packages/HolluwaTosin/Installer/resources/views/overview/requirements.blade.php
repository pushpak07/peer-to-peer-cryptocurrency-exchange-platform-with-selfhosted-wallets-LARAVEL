@extends('installer::layouts.master')
@section('page', __('Requirements'))
@section('content')
    <div class="wizard-header">
        <h3>
            <b>{{__('Installation Wizard')}} </b> <br/> <small>{{__('Just a few steps more. :)')}}</small>
        </h3>
    </div>
    <div class="wizard-navigation">
        <ul>
            <li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
            <li id="active_step"><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
            <li><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
            <li><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a></li>
            <li><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="requirements">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    @include('installer::includes.alerts')
                </div>
                <div class="col-md-12">
                    <h5 class="info-text">
                        {{__("Thank you for purchasing! Next, we need to check your server's configuration.")}}
                    </h5>
                </div>
                <div class="col-md-12">
                    @foreach($requirements['requirements'] as $type => $requirement)
                        <ul class="list-group">
                            <li class="list-group-item active">
                                <strong>{{ strtoupper($type) }}</strong>
                                @if($type == 'php')
                                    <small>({{ $php['minimum'] }} {{__('required')}})</small>
                                    <strong class="pull-right">
                                        @if($php['supported'])
                                            <i class="fa fa-check" style="color: #13ae10"></i>
                                        @else
                                            <i class="fa fa-ban" style="color: #df1711"></i>
                                        @endif
                                    </strong>
                                    <span class="pull-right" style="margin-right: 5px">{{ $php['current'] }}</span>
                                @endif
                            </li>
                            @foreach($requirements['requirements'][$type] as $extention => $enabled)
                                <li class="list-group-item">
                                    <span>{{ $extention }}</span>
                                    <strong class="pull-right">
                                        @if($enabled)
                                            <i class="fa fa-check" style="color: #13ae10"></i>
                                        @else
                                            <i class="fa fa-ban" style="color: #df1711"></i>
                                        @endif
                                    </strong>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="wizard-footer">
        <div class="pull-right">
            @if(!isset($requirements['errors']) && $php['supported'] )
                <a href="{{ route('Installer::overview.permissions') }}" class="btn btn-fill btn-success btn-wd">
                    {{__('Next')}}
                </a>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>

@endsection
