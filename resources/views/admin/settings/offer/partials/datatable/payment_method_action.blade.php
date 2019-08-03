<span>
    <a data-id="{{$data->id}}" class="edit btn btn-icon btn-sm btn-pure primary">
        <i class="ft-edit"></i>
    </a>
    <a href="{{route('admin.settings.offer.delete-payment-method')}}" data-ajax-type="DELETE" data-icon="error" class="btn btn-icon btn-sm btn-pure danger"
       data-swal="confirm-ajax" data-ajax-data='{"id": {{$data->id}}}' data-text="{{__("This payment method will be removed from the records!")}}">
        <i class="ft-trash-2"></i>
    </a>
</span>
