<button class="btn btn-success round payout" data-coin="{{$coin}}" data-coin-name="{{get_coin($coin)}}"
        type="button" data-id="{{$data->id}}" data-amount="{{coin($data->balance, $coin)->getValue()}}">
    <i class="la la-paper-plane"></i>
</button>
