@extends('layouts.master')
@section('page.name', __('Trades'))
@section('page.body')
    <moderation-trades-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <h3 class="content-header-title">{{__('Trades')}}</h3>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-top-primary">
                                    <h4 class="card-title">
                                        {{__('All Trades')}}
                                    </h4>
                                    <a class="heading-elements-toggle">
                                        <i class="la la-ellipsis-h font-medium-3"></i>
                                    </a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
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
                                        <div class="table-responsive">
                                            <table id="trades" class="table table-white-space table-bordered row-grouping display no-wrap icheck table-middle">
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
                                    <h4 class="card-title">{{__('Filter')}}</h4>
                                    <a class="heading-elements-toggle">
                                        <i class="la la-ellipsis-h font-medium-3"></i>
                                    </a>
                                    <div class="heading-elements">
                                        <a href="{{route('market.buy-coin.index')}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="ft-filter white"></i>
                                            {{__('Clear')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <!-- Groups -->
                                <div class="card-body">
                                    <p class="lead">{{__('By Coin')}}</p>
                                    <div class="list-group">
                                        @foreach($coins as $key => $name)
                                            <a href="{{updateUrlQuery(request(), ['coin' => $key])}}"
                                               class="list-group-item {{request()->get('coin') == $key? 'active': ''}}">
                                                {{removeSnakeCase($name)}}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="card-body">
                                    <p class="lead">{{__('By Status')}}</p>
                                    <div class="list-group">
                                        <a href="{{updateUrlQuery(request(), ['status' => 'active'])}}"
                                           class="list-group-item {{request()->get('status') == 'active'? 'active': ''}}">
                                            <span class="badge badge-primary badge-pill float-right">
                                                {{\App\Models\Trade::where('status', 'active')->count()}}
                                            </span>

                                            {{__('Active')}}
                                        </a>

                                        <a href="{{updateUrlQuery(request(), ['status' => 'successful'])}}"
                                           class="list-group-item {{request()->get('status') == 'successful'? 'active': ''}}">
                                            <span class="badge badge-success badge-pill float-right">
                                                {{\App\Models\Trade::where('status', 'successful')->count()}}
                                            </span>

                                            {{__('Successful')}}
                                        </a>

                                        <a href="{{updateUrlQuery(request(), ['status' => 'cancelled'])}}"
                                           class="list-group-item {{request()->get('status') == 'cancelled'? 'active': ''}}">
                                            <span class="badge badge-danger badge-pill float-right">
                                                {{\App\Models\Trade::where('status', 'cancelled')->count()}}
                                            </span>

                                            {{__('Cancelled')}}
                                        </a>

                                        <a href="{{updateUrlQuery(request(), ['status' => 'dispute'])}}"
                                           class="list-group-item {{request()->get('status') == 'dispute'? 'active': ''}}">
                                            <span class="badge badge-warning badge-pill float-right">
                                                {{\App\Models\Trade::where('status', 'dispute')->count()}}
                                            </span>

                                            {{__('Dispute')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </moderation-trades-page>

@endsection

@push('data')
    <script type="text/javascript">
        let url = new URL(window.location);
        let params = url.searchParams;

        window._tableData = [
            // Trades
            {
                'selector': '#trades',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('moderation.trades.data')}}',
                        "type": "POST",
                        "data": {
                            'status': params.get('status'),
                            'coin': params.get('coin'),
                        }
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'coin', orderable: false, searchable: false,},
                        {data: 'amount', searchable: false},
                        {data: 'coin_value', searchable: false},
                        {data: 'rate', searchable: false},
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
