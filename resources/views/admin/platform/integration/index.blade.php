@extends('admin.layouts.master')
@section('page.name', __('Integration'))
@section('page.body')
    <admin-platform-integration-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">
                        {{__('Integration')}}
                    </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.platform.integration') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Landing Page')}}
                                </h4>
                            </div>

                            <div class="card-content">
                                <div class="card-body">
                                    {!! Form::model(platformSettings(), ['class' => 'form form-horizontal']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="form-section">
                                                    <i class="la la-external-link"></i> {{__('Link')}}
                                                </h4>
                                                <div class="bs-callout-success callout-transparent callout-border-left mb-1 p-1">
                                                    <p class="card-text">
                                                        <b>{{__('Hint!')}}</b> {{__('You should use a root domain for your landing page e.g :domain while this platform is installed within a sub domain, say :subdomain', ['domain' => 'http://example.com', 'subdomain' => 'http://dashboard.example.com'])}}
                                                    </p>
                                                </div>

                                                <div class="form-group row">
                                                    {!! Form::label('root_url', __('Root Url'), ['class' => 'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::text('root_url', null, ['class' => 'form-control', 'placeholder' => 'http://example.com']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="form-section">
                                                    <i class="la la-shield"></i> {{__('Public API Access')}}
                                                </h4>

                                                <div class="bs-callout-warning callout-transparent callout-border-left mb-1 p-1">
                                                    <p class="card-text">
                                                        {{__('Set the unique IP address of your server in the field below for improved security, this will restrict public API access to the preset IP addresses separated by comma. Check the documentation for more details.')}}
                                                    </p>
                                                </div>

                                                <div class="form-group row">
                                                    {!! Form::label('allowed_public_ip', __('Allowed Public IP'), ['class' => 'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::text('allowed_public_ip', null, ['class' => 'form-control', 'placeholder' => '63.43.23.103, 34.21.12.221']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions right">
                                        <button type="submit" class="btn ladda-button btn-success">
                                            <i class="la la-check-square-o"></i> {{__('Save')}}
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </admin-platform-integration-page>
@endsection
