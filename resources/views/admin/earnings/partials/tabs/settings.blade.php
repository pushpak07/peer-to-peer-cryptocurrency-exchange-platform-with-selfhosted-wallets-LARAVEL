<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{__('Settings')}}</h4>
            </div>

            <div class="card-content">
                <div class="card-body">
                    {!! Form::open(['url' => route('admin.earnings.update'), 'class' => 'form form-horizontal']) !!}
                    <div class="form-body">

                        <div class="bs-callout-success callout-transparent callout-border-left mb-2 p-1">
                            <p class="card-text">
                                {{__('Set the percentage to be charged on each trade from the seller, this is stored securely on auto generated escrow wallets.')}}
                                {{__('Fees may not be charged on some trades if it is below the minimum transferable amount. Refer to Settings > Transaction to learn more.')}}
                            </p>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('SET_BTC_TRADE_FEE', __('Bitcoin Fee'), ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('SET_BTC_TRADE_FEE', env('SET_BTC_TRADE_FEE'), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('SET_DASH_TRADE_FEE', __('Dash Fee'), ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('SET_DASH_TRADE_FEE', env('SET_DASH_TRADE_FEE'), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('SET_LTC_TRADE_FEE', __('Litecoin Fee'), ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('SET_LTC_TRADE_FEE', env('SET_LTC_TRADE_FEE'), ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="submit" class="btn ladda-button btn-success">
                            <i class="la la-check-square-o"></i> {{__('Save')}}
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
