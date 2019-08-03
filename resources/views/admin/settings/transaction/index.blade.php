@extends('admin.layouts.master')
@section('page.name', __('Transaction Settings'))
@section('page.body')
    <admin-settings-transaction-page inline-template>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">
                        {{__('Transaction Settings')}}
                    </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            {{ Breadcrumbs::render('admin.settings.transaction') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('Settings')}}</h4>
                        </div>

                        <div class="card-content">
                            <div class="card-body">
                                {!! Form::open(['url' => route('admin.settings.transaction.update'), 'class' => 'form form-horizontal']) !!}
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="form-section">
                                                <i class="la la-dollar"></i> {{__('Currency')}}
                                            </h4>

                                            <div class="bs-callout-warning callout-transparent callout-border-left mb-1 p-1">
                                                <p class="card-text">
                                                    {{__('Fees may not be charged on trades if it is below the minimum transferable amount. For instance if $0.16 is the minimum transferable amount, and 1% is set as service fee, fees will only be charged if the amount involved in trade is above $16.')}}
                                                    {{__('You should use this settings to enforce both the minimum & maximum amount to be traded, if you want to ensure that your set percentage fees are charged on every trade. Use bitcoin as a reference, where minimum transferable is 0.00003BTC')}}
                                                </p>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_MIN_OFFER_AMOUNT', __('Min. Offer Amount (USD)'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_MIN_OFFER_AMOUNT', env('SET_MIN_OFFER_AMOUNT'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_MAX_OFFER_AMOUNT', __('Max. Offer Amount (USD)'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_MAX_OFFER_AMOUNT', env('SET_MAX_OFFER_AMOUNT'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_DEFAULT_CURRENCY', __('Currency'), ['class' => 'col-md-3']) !!}

                                                <div class="col-md-9">
                                                    {!! Form::select('SET_DEFAULT_CURRENCY', get_iso_currencies(), null, ['is' => 'select2', 'html-class' => 'form-control', 'required', 'v-model' => 'default_currency']) !!}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="form-section">
                                                <i class="la la-briefcase"></i> {{__('Transaction')}}
                                            </h4>

                                            <div class="bs-callout-info callout-transparent callout-border-left mb-1 p-1">
                                                <p class="card-text">
                                                    {{__('By setting the required number of blocks, the blockchain network is able to dynamically calculate miners fee for each transaction. Setting a high number of blocks results into lesser fees, however it takes more time to get a confirmation. If the number is too high, it may take weeks to get a confirmation or sometimes may not get confirmed at all by miners.')}}
                                                </p>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_TX_NUM_BLOCKS', __('Num. of Blocks'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_TX_NUM_BLOCKS', env('SET_TX_NUM_BLOCKS'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_MIN_TX_CONFIRMATIONS', __('Confirmations'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_MIN_TX_CONFIRMATIONS', env('SET_MIN_TX_CONFIRMATIONS'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="form-section">
                                                <i class="ft-percent"></i> {{__('Profit Per Wallet Limit')}}
                                            </h4>

                                            <div class="bs-callout-info callout-transparent callout-border-left mb-1 p-1">
                                                <p class="card-text">
                                                    {{__('The platform automatically generates new wallets for storing earnings, while a wallet can contain as much coin as possible, it is advised to set a limit for privacy purpose.')}}
                                                </p>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_BTC_PROFIT_PER_WALLET_LIMIT', __('Bitcoin Limit'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_BTC_PROFIT_PER_WALLET_LIMIT', env('SET_BTC_PROFIT_PER_WALLET_LIMIT'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_DASH_PROFIT_PER_WALLET_LIMIT', __('Dash Limit'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_DASH_PROFIT_PER_WALLET_LIMIT', env('SET_DASH_PROFIT_PER_WALLET_LIMIT'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_LTC_PROFIT_PER_WALLET_LIMIT', __('Litecoin Limit'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_LTC_PROFIT_PER_WALLET_LIMIT', env('SET_LTC_PROFIT_PER_WALLET_LIMIT'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="form-section">
                                                <i class="ft-lock"></i> {{__('Locked Balance')}}
                                            </h4>

                                            <div class="bs-callout-info callout-transparent callout-border-left mb-1 p-1">
                                                <p class="card-text">
                                                    {{__('This should be set a little bit above the standard miners fee. It is needed to ensure that the transaction succeeds at all times, i.e to avoid error of insufficient balance.')}}
                                                </p>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_BTC_LOCKED_BALANCE', __('Bitcoin Amount'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_BTC_LOCKED_BALANCE', env('SET_BTC_LOCKED_BALANCE'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_DASH_LOCKED_BALANCE', __('Dash Amount'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_DASH_LOCKED_BALANCE', env('SET_DASH_LOCKED_BALANCE'), ['class' => 'form-control', 'required']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('SET_LTC_LOCKED_BALANCE', __('Litecoin Amount'), ['class' => 'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::text('SET_LTC_LOCKED_BALANCE', env('SET_LTC_LOCKED_BALANCE'), ['class' => 'form-control', 'required']) !!}
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
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-settings-transaction-page>
@endsection

@push('data')
    <script type="text/javascript">
        window._vueData = {!! json_encode([
                'default_currency' => env('SET_DEFAULT_CURRENCY'),
            ]) !!}
    </script>
@endpush
