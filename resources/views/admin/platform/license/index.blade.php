@extends('admin.layouts.master')
@section('page.name', __('License'))
@section('page.body')
    <admin-platform-license-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">
                        {{__('License')}}
                    </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.platform.license') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('License Details')}}
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">

                                    <ul class="list-group mb-1">
                                        <li class="list-group-item">
                                            <span class="float-left">
                                                <i class="la la-star-o mr-1"></i>
                                            </span>

                                            {{__('License Type')}}

                                            <span class="float-right">
                                                {{$purchaseDetails->license()}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="float-left">
                                                <i class="la la-calendar-check-o mr-1"></i>
                                            </span>

                                            {{__('Purchase Date')}}

                                            <span class="float-right">
                                                {{$purchaseDetails->soldAt()->toDayDateTimeString()}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="float-left">
                                                <i class="la la-calendar-o mr-1"></i>
                                            </span>

                                            {{__('Supported Until')}}

                                            <span class="float-right">
                                                {{$purchaseDetails->soldAt()->toDayDateTimeString()}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="float-left">
                                                <i class="la la-link mr-1"></i>
                                            </span>

                                            {{__('Domain')}}

                                            <span class="float-right">
                                                {{$purchaseDetails->domain()}}
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="float-left">
                                                <i class="la la-clock-o mr-1"></i>
                                            </span>

                                            {{__('Cron Last Run')}}

                                            <span class="float-right">
                                                @if($carbon = Cache::get('cron.timestamp'))
                                                    {{$carbon->diffForHumans()}}
                                                @else
                                                    {{__('Not Available')}}
                                                @endif
                                            </span>
                                        </li>
                                    </ul>

                                    {!! Form::open(['class' => 'form form-horizontal', 'method' => 'POST']) !!}
                                    <div class="form-body">
                                        <h4 class="form-section">
                                            <i class="la la-certificate"></i> {{__('Change License')}}
                                        </h4>

                                        <div class="bs-callout-warning callout-border-left mb-1 p-1">
                                            <p class="card-text">
                                                <b>{{__('Important!')}}</b> {{__('Please be aware that current license has been registered to this domain, and cannot be transferred. You may register a new license using the form below.')}}
                                            </p>
                                        </div>

                                        <div class="form-group row">
                                            {!! Form::label('code', __('Purchase Code'), ['class' => 'col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('code', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="form-actions right">
                                            <button type="submit" class="btn btn-success">
                                                {{__('Update')}}
                                            </button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </admin-platform-license-page>
@endsection
