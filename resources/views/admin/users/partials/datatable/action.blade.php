<span class="dropdown">
    <button id="btn-dropdown-{{$data->id}}" type="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="true" class="btn btn-{{($show_trashed)? 'danger': 'primary'}} dropdown-toggle dropdown-menu-right">
        <i class="ft-settings"></i>
    </button>

    <span aria-labelledby="btn-dropdown-{{$data->id}}" class="dropdown-menu mt-1 dropdown-menu-right">
        @if(!$data->trashed())
            @if((Auth::user()->can('edit user settings') || $data->name == Auth::user()->name))
                <a href="{{route('profile.settings.index', ['user' => $data->name])}}" class="dropdown-item">
                    <i class="ft-settings"></i> {{__('Settings')}}
                </a>
            @endif
        @endif

        @if($data->priority() >  Auth::user()->priority())
            @if(!$data->trashed())
                @if($data->status == 'active' && !$data->schedule_deactivate)
                    <a href="{{route('admin.users.deactivate')}}" class="dropdown-item"
                       data-swal="prompt-ajax" data-ajax-type="POST" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="warning"
                       data-ajax-success-action="reloadUsersTable" data-text="{{__("The user will be denied access into the platform until reactivated!")}}">

                        <i class="ft-stop-circle"></i> {{__('Deactivate')}}

                    </a>
                @elseif($data->status == 'inactive')
                    <a href="{{route('admin.users.activate')}}" class="dropdown-item"
                       data-swal="prompt-ajax" data-ajax-type="POST" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="warning"
                       data-ajax-success-action="reloadUsersTable" data-text="{{__("The user will be granted access into the platform!")}}">

                        <i class="ft-play-circle"></i> {{__('Activate')}}

                    </a>
                @endif

                @if(!$data->schedule_delete)
                    <a href="{{route('admin.users.trash')}}" class="dropdown-item"
                       data-swal="prompt-ajax" data-ajax-type="POST" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="warning"
                       data-ajax-success-action="reloadUsersTable" data-text="{{__("The user will be moved to the trash archive. They can be restored at anytime.")}}">

                        <i class="ft-trash"></i> {{__('Trash')}}

                    </a>
                @endif
            @else
                <a href="{{route('admin.users.restore')}}" class="dropdown-item"
                   data-swal="prompt-ajax" data-ajax-type="POST" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="warning"
                   data-ajax-success-action="reloadUsersTable" data-text="{{__("The user record will be removed from the trash archive!")}}">

                    <i class="ft-refresh-cw"></i> {{__('Restore')}}

                </a>

                <a href="{{route('admin.users.delete')}}" class="dropdown-item"
                   data-swal="confirm-ajax" data-ajax-type="POST" data-ajax-data='{"name": "{{$data->name}}"}' data-icon="error"
                   data-ajax-success-action="reloadUsersTable" data-text="{{__("The user will be totally removed from the record. This action cannot be undone!")}}">

                    <i class="ft-trash-2"></i> {{__('Delete')}}

                </a>
            @endif
        @endif
    </span>
</span>
