<div class="card crypto-card-3">
    <div class="card-content">
        <div class="card-body bg-warning cc BTC pb-1">
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

        <div class="card-body">
            @if(getEscrowWallet('btc')->count())
                <div class="table-responsive my-1">
                    <table id="bitcoin-wallets" class="table table-borderless table-striped row-grouping display icheck table-middle">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="all">{{__('Address')}}</th>
                            <th class="all">{{__('Balance')}}</th>
                            <th class="all">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Address')}}</th>
                            <th>{{__('Balance')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="card-text text-center">
                    <img src="{{asset('images/placeholder/no_wallet-address.svg')}}" width="200" height="200">
                </div>

                <div class="card-text text-center">
                    <small> {{__('No escrow wallet available yet!')}} </small>
                </div>
            @endif
        </div>
    </div>
</div>
