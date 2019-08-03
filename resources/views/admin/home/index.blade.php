@extends('admin.layouts.master')
@section('page.name', __('Home'))
@section('page.body')
    <admin-home-page inline-template>
        <div class="content-wrapper">
            <div class="content-body">

                @if($purchaseDetails->isRegularLicense())
                    <div class="bs-callout-info callout-border-left mb-1 p-1">
                        <p class="card-text">
                            <b>{{__('Notice!')}}</b> {{__('You current license type is Regular. While all the functionality is made available, Escrow Fees will not be charged on any trade. An Extended License or greater is required to enable this.')}}
                        </p>
                    </div>
                @endif

                <section>
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="media-middle p-2 bg-info">
                                            <i class="icon-briefcase text-white font-large-2"></i>
                                        </div>
                                        <div class="media-body p-2">
                                            <h4>{{__('Active Offers')}}</h4>
                                            <span>{{__('Total active offers')}}</span>
                                        </div>
                                        <div class="media-right p-2">
                                            <h1>{{$statistics->get('offers_count')}}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-12">
                            <div class="card  overflow-hidden">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="media-middle p-2 bg-success">
                                            <i class="icon-anchor text-white font-large-2"></i>
                                        </div>
                                        <div class="media-body p-2">
                                            <h4>{{__('Total Trades')}}</h4>
                                            <span>{{__('All counts of trade')}}</span>
                                        </div>
                                        <div class="media-right p-2">
                                            <h1>{{$statistics->get('trades_count')}}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="media-middle p-2  bg-secondary">
                                            <i class="icon-user text-white font-large-2"></i>
                                        </div>
                                        <div class="media-body p-2">
                                            <h4>{{__('Registered Users')}}</h4>
                                            <span>{{__('Total registered users')}}</span>
                                        </div>
                                        <div class="media-right p-2">
                                            <h1>{{$statistics->get('users_count')}}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="media-middle p-2 bg-warning">
                                            <i class="icon-credit-card text-white font-large-2"></i>
                                        </div>
                                        <div class="media-body p-2">
                                            <h4>{{__('Net Revenue')}}</h4>
                                            <span>{{__('Total worth of fees')}}</span>
                                        </div>
                                        <div class="media-right p-2">
                                            <h1>{{$statistics->get('sum_revenue')}}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <div class="col mb-1">
                            <h4 class="text-uppercase">{{__('Revenue')}}</h4>
                            <p>{{__('Total Fees Charged')}}</p>
                        </div>

                        <div class="col mb-1">
                            <div class="btn-group float-right">
                                <a href="{{route('admin.earnings.index')}}" class="btn btn-success round box-shadow-1 px-2 white">
                                    <i class="la la-money"></i> {{__('EARNINGS')}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="crypto-stats-2" class="row">
                        <div class="col-xl-4">
                            <div class="card crypto-card-3 bg-gradient-x-warning">
                                <div class="card-content">
                                    <div class="card-body cc BTC pb-1">
                                        <div class="row text-white">
                                            <div class="col-6">
                                                <i class="cc BTC-alt font-large-1" title="BTC"></i>
                                                <h4 class="pt-1 m-0 text-white">
                                                    {{$escrow_wallet->get('btc')['total']}} BTC
                                                </h4>
                                            </div>
                                            <div class="col-6 text-right pl-0">
                                                <h2 class="text-white mb-2 font-large-1">
                                                    {{$escrow_wallet->get('btc')['price']}}
                                                </h2>
                                                <h4 class="text-white">Bitcoin</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card crypto-card-3 bg-gradient-x-primary">
                                <div class="card-content">
                                    <div class="card-body cc DASH pb-1">
                                        <div class="row text-white">
                                            <div class="col-6">
                                                <i class="cc DASH-alt font-large-1" title="DASH"></i>
                                                <h4 class="pt-1 m-0 text-white">
                                                    {{$escrow_wallet->get('dash')['total']}} DASH
                                                </h4>
                                            </div>
                                            <div class="col-6 text-right pl-0">
                                                <h3 class="text-white mb-2 font-large-1">
                                                    {{$escrow_wallet->get('dash')['price']}}
                                                </h3>
                                                <h4 class="text-white">Dash</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card crypto-card-3 bg-gradient-x-grey-blue">
                                <div class="card-content">
                                    <div class="card-body cc LTC pb-1">
                                        <div class="row text-white">
                                            <div class="col-6">
                                                <i class="cc LTC-alt font-large-1" title="LTC"></i>
                                                <h4 class="pt-1 m-0 text-white">
                                                    {{$escrow_wallet->get('ltc')['total']}} LTC
                                                </h4>
                                            </div>
                                            <div class="col-6 text-right pl-0">
                                                <h3 class="text-white mb-2 font-large-1">
                                                    {{$escrow_wallet->get('ltc')['price']}}
                                                </h3>
                                                <h4 class="text-white">Litecoin</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <div class="col-12 mb-1">
                            <h4 class="text-uppercase">{{__('REALTIME STATISTICS')}}</h4>
                            <p>{{__('Activities & engagement chart.')}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                            <div class="card-body text-center" id="visible-offers">
                                                <div class="card-header mb-2">
                                                    <span class="info">{{__('Visible Offers')}}</span>
                                                    <h3 class="display-4 blue-grey darken-1" v-text="formatNumber(visibleOffers.count)"></h3>
                                                </div>
                                                <div class="card-content">
                                                    <input is="knob" type="text" v-model="visibleOffers.percent" class="knob hide-value responsive angle-offset" data-angleOffset="40"
                                                           data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputColor="#e1e1e1"
                                                           data-readOnly="true" data-fgColor="#1E9FF2" data-knob-icon="icon-note">

                                                    <ul class="list-inline clearfix mt-2">
                                                        <li class="border-right-blue-grey border-right-lighten-2 pr-2">
                                                            <h1 class="blue-grey darken-1 text-bold-400" v-text="formatNumber(visibleOffers.percent) + '%'"></h1>
                                                            <span class="success">
                                                                <i class="la la-eye"></i> {{__('Visible')}}
                                                            </span>
                                                        </li>
                                                        <li class="pl-2">
                                                            <h1 class="blue-grey darken-1 text-bold-400" v-text="formatNumber(100 - visibleOffers.percent) + '%'"></h1>
                                                            <span class="danger darken-2">
                                                                <i class="la la-eye-slash"></i> {{__('Not Visible')}}
                                                            </span>
                                                        </li>
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                            <div class="card-body text-center" id="completed-trades">
                                                <div class="card-header mb-2">
                                                    <span class="warning darken-2">{{__('Completed Trades')}}</span>
                                                    <h3 class="display-4 blue-grey darken-1" v-text="formatNumber(completedTrades.count)"></h3>
                                                </div>

                                                <div class="card-content">
                                                    <input is="knob" type="text" v-model="completedTrades.percent" class="knob hide-value responsive angle-offset" data-angleOffset="0"
                                                           data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputColor="#e1e1e1"
                                                           data-readOnly="true" data-fgColor="#FF9149" data-knob-icon="icon-user">

                                                    <ul class="list-inline clearfix mt-2">
                                                        <li>
                                                            <h1 class="blue-grey darken-1 text-bold-400" v-text="formatNumber(completedTrades.dispute)"></h1>
                                                            <span class="warning darken-2">
                                                                <i class="la la-flag"></i> {{__('Total Disputed Trades')}}
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12 col-sm-12 border-right-blue-grey border-right-lighten-5">
                                            <div class="card-body text-center" id="online-users">
                                                <div class="card-header mb-2">
                                                    <span class="success">{{__('Online Users')}}</span>
                                                    <h3 class="display-4 blue-grey darken-1" v-text="formatNumber(onlineUsers.count)"></h3>
                                                </div>
                                                <div class="card-content">

                                                    <input is="knob" type="text" v-model="onlineUsers.percent" class="knob hide-value responsive angle-offset" data-angleOffset="20"
                                                           data-thickness=".15" data-linecap="round" data-width="150" data-height="150" data-inputColor="#e1e1e1"
                                                           data-readOnly="true" data-fgColor="#28D094" data-knob-icon="icon-users">

                                                    <ul class="list-inline clearfix mt-2">
                                                        <li class="border-right-blue-grey border-right-lighten-2 pr-2">
                                                            <h1 class="blue-grey darken-1 text-bold-400" v-text="formatNumber(onlineUsers.percent) + '%'"></h1>
                                                            <span class="success">
                                                                <i class="icon-login"></i> {{__('Online')}}
                                                            </span>
                                                        </li>
                                                        <li class="pl-2">
                                                            <h1 class="blue-grey darken-1 text-bold-400" v-text="formatNumber(100 - onlineUsers.percent) + '%'"></h1>
                                                            <span class="secondary">
                                                                <i class="icon-logout"></i> {{__('Offline')}}
                                                            </span>
                                                        </li>
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </admin-home-page>
@endsection
