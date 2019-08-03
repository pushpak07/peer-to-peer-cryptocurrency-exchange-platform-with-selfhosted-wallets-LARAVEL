@extends('admin.layouts.master')
@section('page.name', __('Notification Settings'))
@section('page.body')
    <admin-settings-notification-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('Notification Settings')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.settings.notification') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in show" id="general"
                                     aria-labelledby="general-tab" aria-expanded="true">
                                    @include('admin.settings.notification.partials.tabs.general')
                                </div>
                                <div class="tab-pane fade" id="user-activated" role="tabpanel"
                                     aria-labelledby="user-activated-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserActivated::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-deactivated" role="tabpanel"
                                     aria-labelledby="user-deactivated-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserDeactivated::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-force-deleted" role="tabpanel"
                                     aria-labelledby="user-force-deleted-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserForceDeleted::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-soft-deleted" role="tabpanel"
                                     aria-labelledby="user-soft-deleted-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserSoftDeleted::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-registered" role="tabpanel"
                                     aria-labelledby="user-registered-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserRegistered::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-restored" role="tabpanel"
                                     aria-labelledby="user-restored-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Authentication\UserRestored::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="email-verification" role="tabpanel"
                                     aria-labelledby="email-verification-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Verification\EmailVerification::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-email-verified" role="tabpanel"
                                     aria-labelledby="user-email-verified-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Verification\UserEmailVerified::getConfiguration()
									])
                                </div>
                                <div class="tab-pane fade" id="user-phone-verified" role="tabpanel"
                                     aria-labelledby="user-phone-verified-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Verification\UserPhoneVerified::getConfiguration()
									])
                                </div>

                                {{-- Trade --}}
                                <div class="tab-pane fade" id="trade-cancelled" role="tabpanel"
                                     aria-labelledby="trade-cancelled-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Cancelled::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-completed" role="tabpanel"
                                     aria-labelledby="trade-completed-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Completed::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-confirmed" role="tabpanel"
                                     aria-labelledby="trade-confirmed-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Confirmed::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-disputed" role="tabpanel"
                                     aria-labelledby="trade-disputed-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Disputed::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-expired" role="tabpanel"
                                     aria-labelledby="trade-expired-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Expired::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-rated" role="tabpanel"
                                     aria-labelledby="trade-rated-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Rated::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="trade-started" role="tabpanel"
                                     aria-labelledby="trade-started-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Trades\Started::getConfiguration()
									])
                                </div>

                                {{-- Transactions --}}
                                <div class="tab-pane fade" id="incoming-confirmed" role="tabpanel"
                                     aria-labelledby="incoming-confirmed-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Transactions\IncomingConfirmed::getConfiguration()
									])
                                </div>

                                <div class="tab-pane fade" id="incoming-unconfirmed" role="tabpanel"
                                     aria-labelledby="incoming-unconfirmed-tab" aria-expanded="false">
                                    @include('admin.settings.notification.partials.tabs.template', [
										'configuration' => \App\Notifications\Transactions\IncomingUnconfirmed::getConfiguration()
									])
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>


            <div class="sidebar-detached sidebar-left">
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
                                            <a class="nav-link active" id="general-tab" data-toggle="pill"
                                               href="#general" role="tab" aria-controls="general" aria-expanded="true">
                                                <i class="ft-globe"></i> {{__('General')}}
                                            </a>
                                        </li>

                                        <li class="pt-1"><p class="lead">{{__('Authentication')}}</p></li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="user-activated-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-activated" role="tab" aria-controls="user-activated">
                                                <i class="ft-play"></i> {{__('User Activated')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-deactivated-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-deactivated" role="tab" aria-controls="user-deactivated">
                                                <i class="ft-stop-circle"></i> {{__('User Deactivated')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-force-deleted-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-force-deleted" role="tab" aria-controls="user-force-deleted">
                                                <i class="ft-trash-2"></i> {{__('User Force Deleted')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-soft-deleted-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-soft-deleted" role="tab" aria-controls="user-soft-deleted">
                                                <i class="ft-trash"></i> {{__('User Soft Deleted')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-registered-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-registered" role="tab" aria-controls="user-registered">
                                                <i class="ft-user-plus"></i> {{__('User Registered')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-restored-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-restored" role="tab" aria-controls="user-restored">
                                                <i class="ft-refresh-ccw"></i> {{__('User Restored')}}
                                            </a>
                                        </li>

                                        <li class="pt-1"><p class="lead">{{__('Verification')}}</p></li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="email-verification-tab" data-toggle="pill" aria-expanded="true"
                                               href="#email-verification" role="tab" aria-controls="email-verification">
                                                <i class="ft-mail"></i> {{__('Email Verification')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-email-verified-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-email-verified" role="tab" aria-controls="user-email-verified">
                                                <i class="ft-mail"></i> {{__('User Email Verified')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="user-phone-verified-tab" data-toggle="pill" aria-expanded="false"
                                               href="#user-phone-verified" role="tab" aria-controls="user-phone-verified">
                                                <i class="ft-phone"></i> {{__('User Phone Verified')}}
                                            </a>
                                        </li>

                                        <li class="pt-1"><p class="lead">{{__('Trades')}}</p></li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-confirmed-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-confirmed" role="tab" aria-controls="trade-cancelled">
                                                <i class="ft-check-circle"></i> {{__('Confirmed Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-cancelled-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-cancelled" role="tab" aria-controls="trade-cancelled">
                                                <i class="ft-stop-circle"></i> {{__('Cancelled Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-completed-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-completed" role="tab" aria-controls="trade-completed">
                                                <i class="ft-check"></i> {{__('Completed Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-rated-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-rated" role="tab" aria-controls="trade-rated">
                                                <i class="ft-star"></i> {{__('Rated Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-started-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-started" role="tab" aria-controls="trade-started">
                                                <i class="ft-plus-circle"></i> {{__('Started Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-disputed-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-disputed" role="tab" aria-controls="trade-disputed">
                                                <i class="ft-flag"></i> {{__('Disputed Trade')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="trade-expired-tab" data-toggle="pill" aria-expanded="false"
                                               href="#trade-expired" role="tab" aria-controls="trade-expired">
                                                <i class="ft-clock"></i> {{__('Expired Trade')}}
                                            </a>
                                        </li>

                                        <li class="pt-1"><p class="lead">{{__('Transactions')}}</p></li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="incoming-confirmed-tab" data-toggle="pill" aria-expanded="false"
                                               href="#incoming-confirmed" role="tab" aria-controls="incoming-confirmed">
                                                <i class="ft-check-square"></i> {{__('Incoming Confirmed')}}
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="incoming-unconfirmed-tab" data-toggle="pill" aria-expanded="false"
                                               href="#incoming-unconfirmed" role="tab" aria-controls="incoming-unconfirmed">
                                                <i class="ft-square"></i> {{__('Incoming Unconfirmed')}}
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
    </admin-settings-notification-page>
@endsection

@push('data')
    <script type="text/javascript">
        window._vueData = {!! json_encode([
                'form' => [
                    'settings' => [
                        'mail_driver' => env('MAIL_DRIVER'),
                        'sms_provider' => env('SMS_PROVIDER'),
                    ]
                ]
            ]) !!}
    </script>
@endpush
