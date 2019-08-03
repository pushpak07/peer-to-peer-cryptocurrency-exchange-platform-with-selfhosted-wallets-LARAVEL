<div class="card crypto-card-3">
    <div class="card-content">
        <div class="card-body bg-secondary cc LTC pb-1">
            <div class="row text-white">
                <div class="col-6">
                    <i class="cc LTC-alt font-large-1" title="LTC"></i>
                    <h4 class="pt-1 m-0 text-white">
                        {{$escrow_wallet->get('ltc')['total']}} LTC
                    </h4>
                </div>
                <div class="col-6 text-right pl-0">
                    <h2 class="text-white mb-2 font-large-1">
                        {{$escrow_wallet->get('ltc')['price']}}
                    </h2>
                    <h4 class="text-white">Litecoin</h4>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(getEscrowWallet('ltc')->count())
                <div class="table-responsive my-1">
                    <table id="litecoin-wallets" class="table table-borderless table-striped row-grouping display icheck table-middle">
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
