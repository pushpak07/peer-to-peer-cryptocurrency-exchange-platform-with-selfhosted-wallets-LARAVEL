@if($data->type == 'sell')
    <a href="{{route('home.offers.index', ['token' => $data->token])}}" class="btn btn-success text-uppercase">
        {{__('BUY')}}
    </a>
@endif

@if($data->type == 'buy')
    <a href="{{route('home.offers.index', ['token' => $data->token])}}" class="btn btn-danger text-uppercase">
        {{__('SELL')}}
    </a>
@endif
