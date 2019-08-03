<span>
    <a data-id="{{$data->id}}" class="edit btn btn-icon btn-sm btn-pure primary">
        <i class="ft-edit"></i>
    </a>
    <a href="{{route('admin.settings.offer.delete-offer-tag')}}" data-ajax-type="DELETE" data-icon="error" class="btn btn-icon btn-sm btn-pure danger"
       data-swal="confirm-ajax" data-ajax-data='{"id": {{$data->id}}}' data-text="{{__("This offer tag will be deleted from records!")}}">
        <i class="ft-trash-2"></i>
    </a>
</span>
