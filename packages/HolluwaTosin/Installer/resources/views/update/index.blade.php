@extends('installer::layouts.master')
@section('page', __('Overview'))
@section('content')
    <div class="wizard-header">
        <h3>
            <b>{{__('Update Wizard')}} </b> <br>
            <small>{{__("You've got some updates.")}}</small>
        </h3>
    </div>

    <div class="wizard-navigation">
        <ul class="steps">
            <li id="active_step"><a href="#overview" data-toggle="tab">{{__('1.')}} {{__('Overview')}}</a></li>
            <li><a href="#finish" data-toggle="tab">{{__('2.')}} {{__('Finish')}}</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="overview">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    @include('installer::includes.alerts')
                </div>
                <div class="col-md-12">
                    <h5 class="info-text">
                        {{__("We need to execute some database migrations, you should make a backup of your database before you proceed.")}}
                    </h5>
                </div>
                <div class="col-sm-4 col-sm-offset-1" style="margin-top: 25px">
                    <div class="picture-container" rel="tooltip" title="{{config('installer.name')}}">
                        <div class="picture">
                            <img src="{{config('installer.thumbnail')}}" class="picture-src"/>
                        </div>
                        <a href="{{config('installer.documentation')}}" style="color: black;">
                            <i class="fa fa-file"></i> {{__('DOCUMENTATION')}}
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 text-center" style="margin-top: 25px">
                    <h3>{{__('There are total of :number migrations to be executed', ['number' => $pendingUpdates])}}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="wizard-footer">
        <div class="pull-right">
            <a href="{{ route('Installer::update.index') }}" class="btn btn-fill btn-success btn-wd"
               onclick="event.preventDefault(); document.getElementById('migrate-form').submit();">
                {{__('Migrate')}}
            </a>
            <form id="migrate-form" action="{{route('Installer::update.index')}}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
