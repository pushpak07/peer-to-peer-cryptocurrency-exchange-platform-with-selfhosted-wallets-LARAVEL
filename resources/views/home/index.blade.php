@extends('layouts.master')
@section('page.name', __('Home'))
@push('css')
    <style rel="stylesheet">
        .slide-fade-enter-active {
            transition: all .3s ease;
        }

        .slide-fade-enter {
            transform: translateY(100px);
            opacity: 0;
        }

        div.rating-list {
            height: 300px;
            position: relative;
        }
    </style>
@endpush

@section('page.body')
    <home-page inline-template>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card crypto-card-3 bg-warning">
                            <div class="card-content">
                                <div class="card-body cc BTC pb-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-white mb-3">
                                                <i class="cc BTC" title="BTC"></i> Bitcoin
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h3 class="text-white mb-2 font-large-1">
                                                {{Auth::user()->wallet('btc')->totalAvailablePrice()}}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <h6 class="text-white mb-1">
                                                {{__('Available')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('btc')->totalAvailable()}}
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h6 class="text-white mb-1">
                                                {{__('Balance')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('btc')->totalBalance()}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card crypto-card-3 bg-primary">
                            <div class="card-content">
                                <div class="card-body cc DASH pb-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-white mb-3">
                                                <i class="cc DASH" title="DASH"></i> Dash
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h3 class="text-white mb-2 font-large-1">
                                                {{Auth::user()->wallet('dash')->totalAvailablePrice()}}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <h6 class="text-white mb-1">
                                                {{__('Available')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('dash')->totalAvailable()}}
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h6 class="text-white mb-1">
                                                {{__('Balance')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('dash')->totalBalance()}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-12">
                        <div class="card crypto-card-3 bg-secondary">
                            <div class="card-content">
                                <div class="card-body cc LTC pb-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-white mb-3">
                                                <i class="cc LTC" title="LTC"></i> Litecoin
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h3 class="text-white mb-2 font-large-1">
                                                {{Auth::user()->wallet('ltc')->totalAvailablePrice()}}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <h6 class="text-white mb-1">
                                                {{__('Available')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('ltc')->totalAvailable()}}
                                            </h4>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h6 class="text-white mb-1">
                                                {{__('Balance')}}
                                            </h6>
                                            <h4 class="text-white">
                                                {{Auth::user()->wallet('ltc')->totalBalance()}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-briefcase"></i> {{__('Active Trades')}}
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
                                    <h5 class="card-text text-center pb-1">
                                        {{__('Amounts are set at fixed price at the point of initiating the trade!')}}
                                    </h5>
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
                            <div class="card-footer text-right">
                                <a href="{{route('profile.trades.index', ['user' => Auth::user()->name])}}"
                                   class="btn {{platformSettings()->template()->button()}}">
                                    {{__('VIEW ALL TRADES')}}
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="ft-shopping-cart"></i> {{__('My Offers')}}
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
                                    <h5 class="card-text text-center pb-1">
                                        {{__('Your offers are only visible to the public when you have enough balance!')}}
                                    </h5>
                                    <div class="table-responsive">
                                        <table id="my-offers" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th class="all">{{__('Status')}}</th>
                                                <th class="all">{{__('Coin')}}</th>
                                                <th class="all">{{__('Type')}}</th>
                                                <th class="all">{{__('Currency')}}</th>
                                                <th class="all">{{__('Amount Range')}}</th>
                                                <th>{{__('Profit Margin')}}</th>
                                                <th>{{__('Payment Method')}}</th>
                                                <th class="all">{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Coin')}}</th>
                                                <th>{{__('Type')}}</th>
                                                <th>{{__('Currency')}}</th>
                                                <th>{{__('Amount Range')}}</th>
                                                <th>{{__('Profit Margin')}}</th>
                                                <th>{{__('Payment Method')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{route('market.create-offer.sell')}}" class="btn {{platformSettings()->template()->button()}}">
                                    {{__('CREATE OFFER')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header {{platformSettings()->template()->background()}}">
                                <h4 class="card-title white">
                                    <i class="ft-paperclip"></i>  {{__('Recent Feedback')}}
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="media-list media-bordered">
                                    <div class="rating-list" ref="ratingScrollWrapper" infinite-wrapper>
                                        <transition-group tag="div" name="slide-fade">
                                            <div class="media" v-for="(rating, id) in ratings.data" :key="rating.id">
                                                <div class="media">
                                                    <span class="media-left">
                                                        <img class="media-object rounded-circle" :src="getProfileAvatar(rating.user)"
                                                             :alt="rating.user.name" style="width: 64px;height: 64px;"/>
                                                    </span>
                                                    <div class="media-body">
                                                        <h5 class="media-heading">
                                                            <a :href="'/profile/'+rating.user.name">
                                                                <b>@{{ rating.user.name }}</b>
                                                            </a>
                                                            {{__('wrote:')}}
                                                        </h5>
                                                        @{{rating.comment}}
                                                        <div class="media-notation mt-1 no-wrap">
                                                            @{{ dateDiffForHumans(rating.created_at) }} |
                                                            <rating :score="rating.rating"></rating>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </transition-group>

                                        <infinite-loading @infinite="ratingInfiniteHandler" ref="ratingInfiniteLoading">
                                            <h3 slot="no-more" class="text-center mt-2">{{__('No more results available!')}}</h3>
                                        </infinite-loading>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="la la-dashboard"></i> {{__('Trade Statistics')}}
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <canvas id="trades-chart" class="height-450"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </home-page>
@endsection


@push('scripts')
    <script type="text/javascript">
		function reloadOffersTable() {
			App._reloadDataTable('#my-offers')
		}
    </script>
@endpush

@push('data')
    <script type="text/javascript">
        window._tableData = [
            // Trades
            {
                'selector': '#trades-list',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('home.trades.data')}}',
                        "type": "POST",
                    },

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'coin', orderable: false},
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

            // My Offers
            {
                'selector': '#my-offers',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('home.offers.data')}}',
                        "type": "POST",
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
                        {data: 'status', orderable: false},
                        {data: 'coin', searchable: false},
                        {data: 'type'},
                        {data: 'currency'},
                        {data: 'amount_range', orderable: false, searchable: false},
                        {data: 'profit_margin'},
                        {data: 'payment_method'},
                        {data: 'action', orderable: false},
                    ]
                }
            }

        ]
    </script>
@endpush

