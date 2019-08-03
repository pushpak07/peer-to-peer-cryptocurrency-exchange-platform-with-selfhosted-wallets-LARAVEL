<div class="card crypto-card-3">
    <div class="card-content">
        <div class="card-body  bg-warning cc BTC pb-1">
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

        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-bold-600" id="bitcoin-address-tab" aria-expanded="true"
                       href="#bitcoin-address" aria-controls="bitcoin-address" data-toggle="tab">
                        <i class="ft-hash"></i> {{__('ADDRESSES')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-bold-600" id="bitcoin-transaction-tab" href="#bitcoin-transaction"
                       aria-controls="bitcoin-transaction" aria-expanded="false" data-toggle="tab">
                        <i class="ft-activity"></i> {{__('TRANSACTION')}}</a>
                </li>
            </ul>
            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="bitcoin-address"
                     aria-labelledby="bitcoin-address-tab" aria-expanded="true">

                    @if(Auth::user()->wallet('btc')->latestAddress())
                        <div class="row">
                            <div class="col-sm-6 py-1 text-center">
                                {!! HTML::image(Auth::user()->wallet('btc')->latestAddressQRCode(), null, ['class' => 'img-thumbnail']) !!}
                            </div>
                            <div class="col-sm-6 py-4 text-center">
                                <div class="card-text size-2x text-uppercase">
                                    {{__('Your bitcoin deposit address is below:')}}
                                </div>

                                <div class="card-text text-center pt-1">
                                    <h2><b>{{Auth::user()->wallet('btc')->latestAddress()}}</b></h2>
                                </div>

                                <div class="card-text text-center pt-1">
                                    <button type="button" class="btn mr-1 mb-1 btn-success"
                                            data-toggle="modal" data-target="#send-bitcoin">
                                        <i class="la la-send"></i> {{__('SEND')}}
                                    </button>
                                </div>

                                <div class="modal fade text-left" id="send-bitcoin" tabindex="-1" role="dialog" aria-labelledby="send-bitcoin-label"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        {!! Form::open(['url' => route('wallet.send', ['coin' => 'btc']), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="send-bitcoin-label">
                                                    <i class="la la-send"></i> {{__('Send')}} Bitcoin
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-body">
                                                    <h3 class="text-center">
                                                        {{__('Available:')}} {{Auth::user()->wallet('btc')->totalAvailable()}} BTC
                                                    </h3>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <label class="col-md-4">{{__('Value')}}</label>
                                                        <div class="col-md-8">
                                                            {!! Form::text('amount', null, ['class' => 'form-control', 'v-model.number' => 'send.btc_value', 'placeholder' => Auth::user()->wallet('btc')->totalAvailable(), 'novalidate']) !!}
                                                            <small class="text-muted">
                                                                {{__('or use -1 as value to empty your balance into the address.')}}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-md-4">{{__('Address')}}</label>
                                                        <div class="col-md-8">
                                                            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => __('Enter receiver address'), 'autocomplete' => 'off']) !!}
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
                                                                {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="form-group row">
                                                            <label class="col-md-4">{{__('2FA Token')}}</label>
                                                            <div class="col-md-8">
                                                                {!! Form::password('token', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
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

                        <div class="row pt-1">
                            <div class="table-responsive">
                                <table id="bitcoin-address-list" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="all">{{__('Address')}}</th>
                                        <th class="all">{{__('Created')}}</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="all">{{__('Address')}}</th>
                                        <th class="all">{{__('Created')}}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="card-text text-center">
                            <img src="{{asset('images/placeholder/no_wallet-address.svg')}}" width="200" height="200">
                        </div>

                        <div class="card-text text-center">
                            <small> {{__('You have not created any public address yet!')}} </small>
                        </div>
                    @endif

                    <div class="row pt-1">
                        <button class="btn btn-warning mx-auto col-lg-8 btn-lg my-1 btn-block"
                                data-swal="confirm-ajax" data-ajax-type="POST" data-icon="warning" type="button"
                                type="button" href="{{route('wallet.generate-address', ['coin' => 'btc'])}}">
                            {{__('Generate New Address')}}
                        </button>
                    </div>
                </div>
                <div class="tab-pane" id="bitcoin-transaction" role="tabpanel"
                     aria-labelledby="bitcoin-transaction-tab" aria-expanded="false">
                    <div class="card-text">
                        <h5 class="text-center">
                            {{__('A minimum of :n confirmations is required before your balance is credited on incoming transaction.', ['n' => config('settings.min_tx_confirmations')])}}
                        </h5>
                    </div>
                    <div class="row pt-1">
                        <div class="table-responsive">
                            <table id="bitcoin-transaction-list" class="table table-white-space table-bordered row-grouping display icheck table-middle">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="all">{{__('Type')}}</th>
                                    <th class="all">{{__('Value')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Confirmations')}}</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="all">{{__('Type')}}</th>
                                    <th class="all">{{__('Value')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Confirmations')}}</th>
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
