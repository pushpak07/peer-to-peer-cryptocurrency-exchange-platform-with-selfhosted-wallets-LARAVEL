@extends('layouts.master')
@section('page.name', __(":name - Trades", ['name' => $user->name]))
@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/profile.css')}}">
@endpush
@section('page.body')
    <profile-page inline-template>
        <div class="content-wrapper">

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">{{strtoupper($user->name) . ' | ' . __('Trades')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('profile', $user->name) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            @include('profile.includes.profile_cover')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-top-primary">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Trades')}}</h4>
                                        <a class="heading-elements-toggle">
                                            <i class="la la-ellipsis-h font-medium-3"></i>
                                        </a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li>
                                                    <a href="{{route('profile.trades.index', ['user' => $user->name])}}"
                                                       class="btn btn-warning btn-sm">
                                                        <i class="ft-filter white"></i> {{__('Clear Filter')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-action="reload">
                                                        <i class="ft-rotate-cw"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="form-group float-left d-none d-md-inline-block">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                    <a href="{{updateUrlQuery(request(), ['status' => 'active'])}}"
                                                       class="btn btn-{{request()->get('status') == 'active' ? 'blue': 'outline-blue'}}">
                                                        {{__('Active')}}
                                                    </a>
                                                    <a href="{{updateUrlQuery(request(), ['status' => 'successful'])}}"
                                                       class="btn btn-{{request()->get('status') == 'successful' ? 'blue': 'outline-blue'}}">
                                                        {{__('Successful')}}
                                                    </a>
                                                    <a href="{{updateUrlQuery(request(), ['status' => 'cancelled'])}}"
                                                       class="btn btn-{{request()->get('status') == 'cancelled' ? 'blue': 'outline-blue'}}">
                                                        {{__('Cancelled')}}
                                                    </a>
                                                    <a href="{{updateUrlQuery(request(), ['status' => 'dispute'])}}"
                                                       class="btn btn-{{request()->get('status') == 'dispute' ? 'blue': 'outline-blue'}}">
                                                        {{__('Dispute')}}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="form-group float-right">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                    <a href="{{updateUrlQuery(request(), ['type' => 'buy'])}}"
                                                       class="btn btn-{{request()->get('type') == 'buy' ? 'success': 'outline-success'}}">
                                                        {{__('Buy')}}
                                                    </a>
                                                    <a href="{{updateUrlQuery(request(), ['type' => 'sell'])}}"
                                                       class="btn btn-{{request()->get('type') == 'sell' ? 'danger': 'outline-danger'}}">
                                                        {{__('Sell')}}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="trades-list" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="all">{{__('Coin')}}</th>
                                                        <th class="all">{{__('Amount')}}</th>
                                                        <th class="none">{{__('Coin Value')}}</th>
                                                        <th>{{__('Rate')}}</th>
                                                        <th>{{__('Method')}}</th>
                                                        <th class="all">{{__('Buyer')}}</th>
                                                        <th class="all">{{__('Seller')}}</th>
                                                        <th class="all">{{__('Status')}}</th>
                                                        <th class="all">{{__('Trade')}}</th>
                                                        <th>{{__('Offer')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>{{__('Coin')}}</th>
                                                        <th>{{__('Amount')}}</th>
                                                        <th>{{__('Coin Value')}}</th>
                                                        <th>{{__('Rate')}}</th>
                                                        <th>{{__('Method')}}</th>
                                                        <th>{{__('Buyer')}}</th>
                                                        <th>{{__('Seller')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th>{{__('Trade')}}</th>
                                                        <th>{{__('Offer')}}</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </profile-page>

@endsection

@push('data')
    <script type="text/javascript">
        let url = new URL(window.location);
        let params = url.searchParams;

        window._tableData = [
            // Trades
            {
                'selector': '#trades-list',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('profile.trades.data', ['user' => $user->name])}}',
                        "type": "POST",
                        "data": {
                            'status': params.get('status'),
                            'type': params.get('type'),
                        }
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'coin', orderable: false, searchable: false},
                        {data: 'amount'},
                        {data: 'coin_value', searchable: false},
                        {data: 'rate'},
                        {data: 'payment_method', orderable: false},
                        {
                            data: 'buyer', orderable: false, searchable: false,
                            createdCell: function (td) {
                                let res = Vue.compile($(td).html());

                                let component = new Vue({
                                    render: res.render,
                                    staticRenderFns: res.staticRenderFns
                                }).$mount();

                                $(td).html(component.$el)
                            }
                        },
                        {
                            data: 'seller', orderable: false, searchable: false,
                            createdCell: function (td) {
                                let res = Vue.compile($(td).html());

                                let component = new Vue({
                                    render: res.render,
                                    staticRenderFns: res.staticRenderFns
                                }).$mount();

                                $(td).html(component.$el)
                            }
                        },
                        {data: 'status', orderable: false, searchable: false},
                        {data: 'trade', searchable: false},
                        {data: 'offer', searchable: false},
                    ]
                }
            },
        ]
    </script>
@endpush
