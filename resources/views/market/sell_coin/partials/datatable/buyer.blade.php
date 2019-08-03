<user-tag :id="{{$data->user->id}}" name="{{$data->user->name}}" avatar="{{getProfileAvatar($data->user)}}"
          presence="{{$data->user->presence}}" last-seen="{{$data->user->last_seen}}" :rating="{{$data->user->averageRating() ?? 0}}"
          country-code="{{$data->user->getCountryCode()}}">
</user-tag>
