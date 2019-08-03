@extends('layouts.master')
@section('page.name', __('Buy Coin'))
@section('page.body')
    <market-buy-coin-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <h3 class="content-header-title">{{__('Buy Coin')}}</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('market.buy_coin') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-detached content-right">
                <div class="content-body">
                    <section class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-top-primary">
                                    <h4 class="card-title">
                                        {{__('Open Offers')}}
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
                                            {{__('Try to avoid traders that were last seen over a day ago. They may not be responsive!')}}
                                        </h5>

                                        <div class="table-responsive">
                                            <table id="offers" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="all">{{__('Seller')}}</th>
                                                    <th class="all">{{__('Coin')}}</th>
                                                    <th>{{__('Pay with')}}</th>
                                                    <th>{{__('Currency')}}</th>
                                                    <th class="all">{{__('Amount Range')}}</th>
                                                    <th class="all">{{__('Worth')}}</th>
                                                    <th>{{__('Coin Rate')}}</th>
                                                    <th class="all">{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>{{__('Seller')}}</th>
                                                    <th>{{__('Coin')}}</th>
                                                    <th>{{__('Pay with')}}</th>
                                                    <th>{{__('Currency')}}</th>
                                                    <th>{{__('Amount Range')}}</th>
                                                    <th>{{__('Worth')}}</th>
                                                    <th>{{__('Coin Rate')}}</th>
                                                    <th>{{__('Action')}}</th>
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
                                    <p class="lead">{{__('By Payment')}}</p>
                                    <form>
                                        <div class="form-group">
                                            {!! Form::label('payment_method', __('Method')) !!}
                                            {!! Form::select('payment_method', get_payment_methods(), null, ['is' => 'select2', 'html-class' => 'form-control', 'placeholder' => __('Select method...'), 'novalidate', 'v-model' => 'payment_method']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('amount', __('Amount')) !!}
                                            {!! Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter amount...'), 'novalidate', 'v-model.number' => 'amount']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('currency', __('Currency')) !!}
                                            {!! Form::select('currency', get_iso_currencies(), null, ['is' => 'select2', 'html-class' => 'form-control', 'placeholder' => __('Select currency...'), 'novalidate', 'v-model' => 'currency']) !!}
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-block">
                                            {{__('APPLY')}}
                                        </button>
                                    </form>
                                </div>
                                <!--/ Groups-->
                            </div>
                        </div>
                        <!--/ Predefined Views -->
                    </div>
                </div>
            </div>
        </div>
    </market-buy-coin-page>
@endsection

@push('data')
    <script type="text/javascript">
        var url = new URL(window.location);
        var params = url.searchParams;

        window._tableData = [
            // Buy Offers
            {
                'selector': '#offers',
                'options': {
                    "ajax": {
                        "async": true,
                        "url": '{{route('market.buy-coin.data')}}',
                        "type": "POST",
                        "data": {
                            'amount': params.get('amount'),
                            'payment_method': params.get('payment_method'),
                            'currency': params.get('currency'),
                            'coin': params.get('coin')
                        }
                    },

                    searching: false,

                    columns: [
                        {data: null, defaultContent: ''},
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
                        {data: 'coin', searchable: false},
                        {data: 'payment_method', orderable: false, searchable: false},
                        {data: 'currency', searchable: false},
                        {data: 'amount_range', orderable: false, searchable: false},
                        {data: 'worth', searchable: false},
                        {data: 'coin_rate', searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ]
                }
            }

        ]
    </script>
@endpush
