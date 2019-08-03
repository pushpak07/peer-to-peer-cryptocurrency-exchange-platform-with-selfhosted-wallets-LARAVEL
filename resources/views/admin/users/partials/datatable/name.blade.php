<div class="media">
    <div class="media-left pr-1">
        <span class="avatar avatar-sm {{getPresenceClass($data)}} rounded-circle">
            <img src="{{getProfileAvatar($data)}}" alt="avatar"><i></i>
        </span>
    </div>

    <div class="media-body media-middle">
        <a href="{{!$data->trashed()? route("profile.index", ["user" => $data->name]): '#'}}" class="media-heading">
            {{$data->name}}
        </a>
    </div>
</div>
