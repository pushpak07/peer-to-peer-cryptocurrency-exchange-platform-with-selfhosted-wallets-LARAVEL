<user-tag :id="{{$data->buyer()->id}}" name="{{$data->buyer()->name}}" avatar="{{getProfileAvatar($data->buyer())}}"
          presence="{{$data->buyer()->presence}}" last-seen="{{$data->buyer()->last_seen}}" :rating="{{$data->buyer()->averageRating() ?? 0}}"
		  country-code="{{$data->buyer()->getCountryCode()}}">
</user-tag>
