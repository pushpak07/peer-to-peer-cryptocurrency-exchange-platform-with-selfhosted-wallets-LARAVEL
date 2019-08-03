@extends('installer::layouts.master')
@section('page', __('Environment'))
@section('content')
    <form method="POST">
        {{csrf_field()}}
        <div class="wizard-header">
            <h3>
                <b>{{__('Installation Wizard')}} </b> <br/>
                <small>{{__('We need to gather some important information...')}}</small>
            </h3>
        </div>
        <div class="wizard-navigation">
            <ul class="steps">
                <li><a href="#welcome" data-toggle="tab">{{__('1.')}} {{__('Welcome')}}</a></li>
                <li><a href="#requirements" data-toggle="tab">{{__('2.')}} {{__('Requirements')}}</a></li>
                <li><a href="#permissions" data-toggle="tab">{{__('3.')}} {{__('Permissions')}}</a></li>
                <li id="active_step"><a href="#environment" data-toggle="tab">{{__('4.')}} {{__('Environments')}}</a>
                </li>
                <li><a href="#finish" data-toggle="tab">{{__('5.')}} {{__('Finish')}}</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="environment">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        @include('installer::includes.alerts')
                    </div>
                    <div class="col-md-12">
                        @if($data = $content['app'])
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="info-text"> {{__("Setup your application details..")}} </h5>
                                </div>
                            </div>
                            @include('installer::includes.forms', ['content' => $data])
                        @endif

                        @if($data = $content['db'])
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="info-text"> {{__("Setup your database connection..")}} </h5>
                                </div>
                            </div>

                            @include('installer::includes.forms', ['content' => $data])
                        @endif

                        @if($data = $content['broadcast'])
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="info-text">{{__("Setup your application broadcast server..")}}</h5>
                                </div>
                            </div>

                            @include('installer::includes.forms', ['content' => $data])
                        @endif

                        @if($data = $content['mail'])
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="info-text"> {{__("Setup your application mail server..")}} </h5>
                                </div>
                            </div>

                            @include('installer::includes.forms', ['content' => $data])
                        @endif


                        @if($data = $content['extras'])
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="info-text"> {{__("We need some extra information..")}} </h5>
                                </div>
                            </div>

                            @include('installer::includes.forms', ['content' => $data])
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="wizard-footer">
            <div class="pull-right">
                <input type="submit" class="btn btn-fill btn-success btn-wd" value="{{__('Submit')}}"/>
            </div>
            <div class="clearfix"></div>
        </div>

    </form>
@endsection
