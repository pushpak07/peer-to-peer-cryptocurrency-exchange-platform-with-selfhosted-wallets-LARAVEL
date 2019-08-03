<user-tag :id="{{$data->seller()->id}}" name="{{$data->seller()->name}}" avatar="{{getProfileAvatar($data->seller())}}"
          presence="{{$data->seller()->presence}}" last-seen="{{$data->seller()->last_seen}}" :rating="{{$data->seller()->averageRating() ?? 0}}"
          country-code="{{$data->seller()->getCountryCode()}}">
</user-tag>
