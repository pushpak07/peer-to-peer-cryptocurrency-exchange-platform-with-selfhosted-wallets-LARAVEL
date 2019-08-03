@if($data->status)
    <a href="{{route('home.offers.toggle', ['token' => $data->token])}}" data-ajax-success-action="reloadOffersTable"
       data-swal="confirm-ajax" data-ajax-type="POST" data-icon="warning" class="btn btn-icon btn-sm btn-pure darken-4 grey-blue"
       data-text="{{__("This offer will be disabled and hidden from other users!")}}">
        <i class="la la-toggle-off"></i>
    </a>
@else
    <a href="{{route('home.offers.toggle', ['token' => $data->token])}}" data-ajax-success-action="reloadOffersTable"
       data-swal="confirm-ajax" data-ajax-type="POST" data-icon="success" class="btn btn-icon btn-sm btn-pure darken-4 grey-blue"
       data-text="{{__("This offer will be enabled and shown to other users!")}}">
        <i class="la la-toggle-on"></i>
    </a>
@endif

<a href="{{route('home.offers.edit', ['token' => $data->token])}}" class="btn btn-icon btn-sm btn-pure success">
    <i class="la la-pencil"></i>
</a>

@if($data->status)
    <a href="{{route('home.offers.index', ['token' => $data->token])}}" class="btn btn-icon btn-sm btn-pure primary">
        <i class="la la-eye"></i>
    </a>
@endif

<a href="{{route('home.offers.delete', ['token' => $data->token])}}" data-ajax-success-action="reloadOffersTable"
   data-swal="confirm-ajax" data-ajax-type="DELETE" data-icon="error" class="btn btn-icon btn-sm btn-pure secondary"
   data-text="{{__("This offer will be removed from the marketplace!")}}">
    <i class="la la-trash"></i>
</a>
