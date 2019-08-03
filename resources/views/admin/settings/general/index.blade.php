@extends('admin.layouts.master')
@section('page.name', __('General Settings'))
@section('page.body')
    <admin-settings-general-page inline-template>
        <div class="content-wrapper">

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('General Settings')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.settings.general') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in show" id="global"
                                     aria-labelledby="global-tab" aria-expanded="true">
                                    @include('admin.settings.general.partials.tabs.global')
                                </div>
                                <div class="tab-pane fade" id="oer" role="tabpanel"
                                     aria-labelledby="oer-tab" aria-expanded="false">
                                    @include('admin.settings.general.partials.tabs.oer')
                                </div>
                                <div class="tab-pane fade" id="google" role="tabpanel"
                                     aria-labelledby="google-tab" aria-expanded="false">
                                    @include('admin.settings.general.partials.tabs.google')
                                </div>
                                <div class="tab-pane fade" id="bitgo" role="tabpanel"
                                     aria-labelledby="bitgo-tab" aria-expanded="false">
                                    @include('admin.settings.general.partials.tabs.bitgo')
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>


            <div class="sidebar-detached sidebar-sticky sidebar-left">
                <div class="sidebar">
                    <div class="bug-list-sidebar-content">
                        <!-- Predefined Views -->
                        <div class="card">
                            <div class="card-head">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Navigation')}}</h4>
                                </div>
                            </div>
                            <div class="card-content">
                                <!-- Groups -->
                                <div class="card-body">

                                    <ul class="nav nav-pills nav-pill-with-active-bordered flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="global-tab" data-toggle="pill"
                                               href="#global" role="tab" aria-controls="global" aria-expanded="true">
                                                <i class="ft-globe"></i> {{__('Global')}}
                                            </a>
                                        </li>

                                        <li class="pt-1"><p class="lead">{{__('Services')}}</p></li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="oer-tab" data-toggle="pill" aria-expanded="false"
                                               href="#oer" role="tab" aria-controls="oer">
                                                {{__('OER')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="google-tab" data-toggle="pill" aria-expanded="false"
                                               href="#google" role="tab" aria-controls="google">
                                                {{__('Google')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="bitgo-tab" data-toggle="pill" aria-expanded="false"
                                               href="#bitgo" role="tab" aria-controls="bitgo">
                                                {{__('BitGo')}}
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                                <!--/ Groups-->
                            </div>
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </admin-settings-general-page>

@endsection

@push('data')
    <script type="text/javascript">
        window._vueData = {!! json_encode([
                'form' => [
                    'settings' => [
                        'broadcast_driver' => env('BROADCAST_DRIVER'),
                    ]
                ]
            ]) !!}
    </script>
@endpush
