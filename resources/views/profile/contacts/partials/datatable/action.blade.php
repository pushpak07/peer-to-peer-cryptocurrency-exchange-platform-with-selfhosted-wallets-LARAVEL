<span class="dropdown">
    <button id="btn-dropdown-{{$data->id}}" type="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right">
        <i class="ft-settings"></i>
    </button>

    <span aria-labelledby="btn-dropdown-{{$data->id}}" class="dropdown-menu mt-1 dropdown-menu-right">

        @if ($data->pivot->state == null || $data->pivot->state == "block")

            <a href="{{route("profile.contacts.trust", ["user" => $user->name])}}" class="dropdown-item"
               data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$data->name}}"}'
               data-ajax-success-action="reloadContactsTable" data-text="{{__("This will grant the user to see your trusted only offers!")}}">

                <i class="ft-star"></i> {{__("Trust")}}

            </a>

        @elseif ($data->pivot->state == "trust")

            <a href="{{route("profile.contacts.untrust", ["user" => $user->name])}}" class="dropdown-item"
               data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$data->name}}"}'
               data-ajax-success-action="reloadContactsTable" data-text="{{__("This will revoke the user from seeing your trusted only offers!")}}">

                <i class="ft-star"></i> {{__("Untrust")}}

            </a>

        @endif

        @if ($data->pivot->state == null || $data->pivot->state == "trust")

            <a href="{{route("profile.contacts.block", ["user" => $user->name])}}" class="dropdown-item"
               data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$data->name}}"}'
               data-ajax-success-action="reloadContactsTable" data-text="{{__("This will hide your offers from the user!")}}">

                <i class="ft-stop-circle"></i> {{__("Block")}}

            </a>

        @elseif ($data->pivot->state == "block")
            <a href="{{route("profile.contacts.unblock", ["user" => $user->name])}}" class="dropdown-item"
               data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$data->name}}"}'
               data-ajax-success-action="reloadContactsTable" data-text="{{__("This will show your offers to the user!")}}">

                <i class="ft-stop-circle"></i> {{__("Unblock")}}

            </a>
        @endif

        <a href="{{route("profile.contacts.delete", ["user" => $user->name])}}" class="dropdown-item"
           data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="warning"
           data-ajax-success-action="reloadContactsTable" data-text="{{__("The user will be totally removed from your contact list!")}}">

            <i class="ft-trash"></i> {{__("Delete")}}

        </a>

    </span>
</span>
