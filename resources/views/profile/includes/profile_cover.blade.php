<div class="card profile-with-cover">
    <div class="card-img-top img-fluid bg-cover height-200 {{platformSettings()->template()->backgroundGradient()}}"></div>
    <div class="media profile-cover-details w-100">
        <div class="media-left pl-2 pt-2">
            <a href="#" class="profile-image" v-bind:class="avatarPresenceObject">
                <img src="{{getProfileAvatar($user)}}" class="rounded-circle img-border height-100" alt="Card image">
                <i></i>
            </a>
        </div>
        <div class="media-body pt-3 px-2">
            <div class="row">
                <div class="col d-none d-sm-inline-block">
                    <h3 class="card-title">{{$user->name}}</h3>
                </div>
                <div class="col text-right">
                    @if($user->id == Auth::user()->id)
                        <a href="{{route('profile.contacts.index', ['user' => $user->name])}}" class="btn box-shadow-1 round btn-primary">
                            <i class="la la-tty"></i>
                            <span class="d-none d-lg-inline">{{__('My Contacts')}}</span>
                        </a>
                        <a href="{{route('profile.settings.index', ['user' => $user->name])}}"
                           class="btn box-shadow-1 round btn-success">
                            <i class="la la-gear"></i>
                        </a>
                    @else
                        @if($contact = Auth::user()->contacts()->where('name', $user->name)->first())
                            <a href="{{route('profile.contacts.delete', ['user' => Auth::user()->name])}}" class="btn box-shadow-1 round btn-danger"
                               data-swal="confirm-ajax" data-ajax-type="PUT" data-icon="warning" data-ajax-data='{"name": "{{$user->name}}"}'>

                                <i class="ft-phone-off"></i>
                                <span class="d-none d-lg-inline">{{__('Remove Contact')}}</span>

                            </a>
                            <button type="button" class="btn box-shadow-1 round btn-success"
                                    aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                <i class="la la-bookmark"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if ($contact->pivot->state == null || $contact->pivot->state == "block")

                                    <a href="{{route("profile.contacts.trust", ["user" => Auth::user()->name])}}" class="dropdown-item"
                                       data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$contact->name}}"}'
                                       data-text="{{__("This will grant the user to see your trusted only offers!")}}">

                                        <i class="ft-star"></i> {{__("Trust")}}

                                    </a>

                                @elseif ($contact->pivot->state == "trust")

                                    <a href="{{route("profile.contacts.untrust", ["user" => Auth::user()->name])}}" class="dropdown-item"
                                       data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$contact->name}}"}'
                                       data-text="{{__("This will revoke the user from seeing your trusted only offers!")}}">

                                        <i class="ft-star"></i> {{__("Untrust")}}

                                    </a>

                                @endif

                                @if ($contact->pivot->state == null || $contact->pivot->state == "trust")

                                    <a href="{{route("profile.contacts.block", ["user" => Auth::user()->name])}}" class="dropdown-item"
                                       data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$contact->name}}"}'
                                       data-text="{{__("This will hide your offers from the user!")}}">

                                        <i class="ft-stop-circle"></i> {{__("Block")}}

                                    </a>

                                @elseif ($contact->pivot->state == "block")
                                    <a href="{{route("profile.contacts.unblock", ["user" => Auth::user()->name])}}" class="dropdown-item"
                                       data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$contact->name}}"}'
                                       data-text="{{__("This will show your offers to the user!")}}">

                                        <i class="ft-stop-circle"></i> {{__("Unblock")}}

                                    </a>
                                @endif
                            </div>
                        @else
                            <a href="{{route('profile.contacts.add', ['user' => Auth::user()->name])}}" class="btn box-shadow-1 round btn-primary"
                               data-swal="confirm-ajax" data-ajax-type="PUT" data-ajax-data='{"name": "{{$user->name}}"}'>

                                <i class="ft-phone"></i>
                                <span class="d-none d-lg-inline">{{__('Add Contact')}}</span>

                            </a>
                        @endif

                        @if(Auth::user()->can('edit user settings') && !$user->schedule_deactivate)
                            <a href="{{route('profile.deactivate', ['user' => $user->name])}}" data-ajax-type="POST"
                               data-swal="prompt-ajax" data-icon="warning" class="btn box-shadow-1 round btn-warning"
                               data-text="{{__("The user will be denied access into the platform!")}}" data-ajax-success-action="goHome">
                                <i class="la la-ban"></i>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-md navbar-light align-self-md-end">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-target="#profile-menu"
                data-toggle="collapse" aria-expanded="false"
                aria-label="Toggle navigation" aria-controls="profile-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="profile-menu">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item {{request()->route()->getName() == 'profile.index' ? 'active': ''}}">
                    <a class="nav-link" href="{{route('profile.index', ['user' => $user->name])}}">
                        <i class="la la-user"></i> {{__('My Profile')}}
                    </a>
                </li>

                @alloworcan('view user details', $user->id)
                <li class="nav-item {{request()->route()->getName() == 'profile.trades.index' ? 'active': ''}}">
                    <a class="nav-link" href="{{route('profile.trades.index', ['user' => $user->name])}}">
                        <i class="la la-envelope"></i> {{__('My Trades')}}
                    </a>
                </li>
                <li class="nav-item {{request()->route()->getName() == 'profile.notifications.index' ? 'active': ''}}">
                    <a class="nav-link" href="{{route('profile.notifications.index', ['user' => $user->name])}}">
                        <i class="la la-bell-o"></i> {{__('Notifications')}}
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                @endalloworcan
            </ul>
        </div>
    </nav>
</div>

@push('data')
    <script type="text/javascript">
        window._vueData = {!! json_encode([
            'profile' => [
                'name' => $user->name,
                'lastSeen' => $user->last_seen,
                'presence' => $user->presence,
                'id' => $user->id,
            ],
        ]) !!}

        function goHome() {
            window.location.href = route('home.index');
        }
    </script>
@endpush
