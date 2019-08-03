@extends('admin.layouts.master')
@section('page.name', __('Customize'))

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/vendors/codemirror/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/vendors/codemirror/theme/monokai.css')}}">
@endpush

@section('page.body')
    <admin-platform-customize-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('Customize')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.platform.customize') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Template Setup')}}</h4>
                            </div>

                            <div class="card-content">
                                <div class="card-body">
                                    {!! Form::model(platformSettings(), ['class' => 'form form-horizontal']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="form-section">
                                                    <i class="la la-cube"></i> {{__('Template')}}
                                                </h4>
                                                <div class="form-group row">
                                                    {!! Form::label('template', __('Template'), ['class' => 'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::select('template', platform_templates(), null, ['is' => 'select2', 'class' => 'form-control', 'required']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="form-section">
                                                    <i class="la la-paint-brush"></i> {{__('Theme')}}
                                                </h4>
                                                <div class="form-group row">
                                                    {!! Form::label('theme_color', __('Color'), ['class' => 'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::select('theme_color', platform_theme_colors(), null, ['is' => 'select2', 'class' => 'form-control', 'required']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="bs-callout-warning callout-border-left mb-1 p-1">
                                            <p class="card-text">
                                                {{__('This will be applied to all pages in the User Area.')}}
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label('style', __('Custom CSS')) !!}
                                            {!! Form::textarea('style', null, ['rows' => 6, 'class' => 'form-control']) !!}
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
    </admin-platform-customize-page>
@endsection

@push('scripts')
    <script src="{{asset('js/vendors/codemirror/codemirror.js')}}"></script>
    <script src="{{asset('js/vendors/codemirror/mode/css/css.js')}}"></script>

    <script type="text/javascript">
        (function (window, document, $) {
            'use strict';

            let code = document.getElementById("style");

            if (typeof CodeMirror !== "undefined") {
                CodeMirror.fromTextArea(code, {
                    lineNumbers: true,
                    styleActiveLine: true,
                    mode: "css",
                    matchBrackets: true,
                    theme: "monokai",
                });
            }
        })(window, document, jQuery);
    </script>
@endpush
