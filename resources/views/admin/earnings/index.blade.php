@extends('admin.layouts.master')
@section('page.name', __('Earnings'))
@section('page.body')
    <admin-earnings-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{__('Earnings')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.earnings') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">

                    @if($purchaseDetails->isRegularLicense())
                        <div class="bs-callout-info callout-border-left mb-1 p-1">
                            <p class="card-text">
                                <b>{{__('Notice!')}}</b> {{__('You current license type is Regular. While all the functionality is made available, Escrow Fees will not be charged on any trade. An Extended License or greater is required to enable this.')}}
                            </p>
                        </div>
                    @endif

                    <section class="row">
                        <div class="col-12">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in show" id="bitcoin"
                                     aria-labelledby="bitcoin-tab" aria-expanded="true">
                                    @include('admin.earnings.partials.tabs.bitcoin')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="dash"
                                     aria-labelledby="dash-tab" aria-expanded="false">
                                    @include('admin.earnings.partials.tabs.dash')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="litecoin"
                                     aria-labelledby="litecoin-tab" aria-expanded="false">
                                    @include('admin.earnings.partials.tabs.litecoin')
                                </div>

                                <div class="tab-pane fade" id="settings" role="tabpanel"
                                     aria-labelledby="settings-tab" aria-expanded="false">
                                    @include('admin.earnings.partials.tabs.settings')
                                </div>
                            </div>
                        </div>

                    </section>

                    <div class="modal fade text-left" id="payout" tabindex="-1" role="dialog" aria-labelledby="payout-label"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            {!! Form::open(['url' => route('admin.earnings.payout'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title" id="payout-label">
                                        <i class="la la-send"></i>
                                        <span v-text="payout.coinName"></span> {{__('Payout')}}
                                    </h4>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-body">
                                        <h3 class="text-center">
                                            {{__('Amount:')}}
                                            <span v-text="payout.amount"></span>
                                            <span v-text="payout.coin.toUpperCase()"></span>
                                        </h3>
                                        <hr>

                                        {!! Form::hidden('id', null, ['v-model' => 'payout.id']) !!}

                                        {!! Form::hidden('coin', null, ['v-model' => 'payout.coin']) !!}

                                        <div class="form-group row">
                                            <label class="col-md-4">
                                                <span v-text="payout.coinName"></span> {{__('Address')}}
                                            </label>

                                            <div class="col-md-8">
                                                {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => __('Enter receiver address')]) !!}
                                                <small class="help-block">
                                                    {{__('Ensure that the outgoing address is correct.')}}
                                                </small>
                                            </div>
                                        </div>
                                        <hr>

                                        <p class="text-center bock-tag">
                                            <span class="badge badge-danger">{{__('Security:')}}</span> {{__('Please verify your identity!')}}
                                        </p>

                                        @if(!Auth::user()->getSetting()->outgoing_transfer_2fa)
                                            <div class="form-group row">
                                                <label class="col-md-4">{{__('Password')}}</label>
                                                <div class="col-md-8">
                                                    {!! Form::password('password', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group row">
                                                <label class="col-md-4">{{__('2FA Token')}}</label>
                                                <div class="col-md-8">
                                                    {!! Form::password('token', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">
                                        {{__('Close')}}
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        {{__('Send')}}
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="sidebar-detached sidebar-left">
                <div class="sidebar">
                    <div class="card">
                        <div class="card-content">
                            <!-- Groups -->
                            <div class="card-body">
                                <ul class="nav nav-pills nav-pill-with-active-bordered flex-column">

                                    <li class="pt-1"><p class="lead">{{__('Wallets')}}</p></li>

                                    <li class="nav-item">
                                        <a class="nav-link active" id="bitcoin-tab" data-toggle="pill"
                                           href="#bitcoin" role="tab" aria-controls="bitcoin" aria-expanded="true">
                                            <i class="cc BTC"></i> Bitcoin
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="dash-tab" data-toggle="pill"
                                           href="#dash" role="tab" aria-controls="dash" aria-expanded="false">
                                            <i class="cc DASH"></i> Dash
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" id="litecoin-tab" data-toggle="pill"
                                           href="#litecoin" role="tab" aria-controls="litecoin" aria-expanded="false">
                                            <i class="cc LTC"></i> Litecoin
                                        </a>
                                    </li>

                                    <li class="pt-1"><p class="lead">{{__('Miscellaneous')}}</p></li>


                                    <li class="nav-item">
                                        <a class="nav-link" id="settings-tab" data-toggle="pill" aria-expanded="false"
                                           href="#settings" role="tab" aria-controls="settings">
                                            <i class="la la-gear"></i> {{__('Settings')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--/ Groups-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-earnings-page>
@endsection

@push('data')
    <script type="text/javascript">
        window._tableData = [
            // Bitcoin Wallets
            {
                'selector': '#bitcoin-wallets',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.earnings.data', ['coin' => 'btc'])}}',
                        "type": "POST",
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'address', searchable: false},
                        {data: 'balance', searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ]
                }
            },

            // Dash Wallets
            {
                'selector': '#dash-wallets',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.earnings.data', ['coin' => 'dash'])}}',
                        "type": "POST",
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'address', searchable: false},
                        {data: 'balance', searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ]
                }
            },

            // Litecoin Wallets
            {
                'selector': '#litecoin-wallets',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('admin.earnings.data', ['coin' => 'ltc'])}}',
                        "type": "POST",
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'address', searchable: false},
                        {data: 'balance', searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ]
                }
            },
        ];
    </script>
@endpush
