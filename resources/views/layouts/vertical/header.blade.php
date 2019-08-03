<app-navigation inline-template>
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-hide-on-scrol navbar-without-dd-arrow fixed-top navbar-semi-light navbar-shadow {{platformSettings()->template()->background()}}">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a
                            class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item">
                        <a class="navbar-brand" href="{{url('/')}}">
                            <img class="brand-logo" alt="{{config('app.name')}}" src="{{config('app.logo_icon') ?: asset('/images/icon/logo-sm.png')}}">
                            <h3 class="brand-text">{{config('app.name')}}</h3>
                        </a>
                    </li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile">
                            <i class="la la-ellipsis-v"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                <i class="ft-menu"></i>
                            </a>
                        </li>

                        @can('access admin panel')
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link" href="{{route('admin.home.index')}}">
                                {{__('Admin Panel')}}
                            </a>
                        </li>
                        @endcan

                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link nav-link-expand" href="#">
                                <i class="ficon ft-maximize"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="mr-1">
                                    <span class="greeting"> {{__('Hello!')}} </span>
                                    <span class="user-name text-bold-700">{{Auth::user()->name}}</span>
                                </span>
                                <span class="avatar">
                                    <img src="{{getProfileAvatar(Auth::user())}}" alt="avatar"><i></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('profile.index', ['user' => Auth::user()->name])}}">
                                    <i class="ft-user"></i> {{__('My Profile')}}
                                </a>
                                <a class="dropdown-item" href="{{route('profile.trades.index', ['user' => Auth::user()->name])}}">
                                    <i class="ft-mail"></i> {{__('My Trades')}}
                                </a>
                                <a class="dropdown-item" href="{{route('profile.settings.index', ['user' => Auth::user()->name])}}">
                                    <i class="ft-settings"></i> {{__('Settings')}}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
    
                        <li class="dropdown dropdown-language nav-item">
                            @if($locale = LaravelLocalization::getCurrentLocale())
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="flag-icon flag-icon-{{getLocaleRegion($locale)}}"></i>
                                    <span class="selected-language"></span>
                                </a>
                            @endif
        
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                @foreach(getAvailableLocales() as $locale => $language)
                                    @if($url = LaravelLocalization::getLocalizedURL($locale))
                                        <a class="dropdown-item" href="{{$locale != LaravelLocalization::getCurrentLocale()? $url: '#' }}">
                                            <i class="flag-icon flag-icon-{{getLocaleRegion($locale)}}"></i>
                                            {{$language}}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                        
                        <li class="dropdown dropdown-notification nav-item">
                            <a :class="bellAnimation" class="nav-link nav-link-label position-relative animated infinite"
                               @click="hideBellAlert()" href="#" data-toggle="dropdown">
                                <i class="ficon ft-bell"></i>
                                <span class="badge badge-pill badge-default badge-danger badge-default badge-up badge-glow" v-if="notifications.total">
                                    @{{notifications.total}}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">{{__('Notifications')}}</span>
                                    </h6>
                                    <a href="{{route('profile.notifications.index', ['user' => Auth::user()->name])}}"
                                       class="notification-tag badge badge-default badge-danger float-right m-0">
                                        {{__('More')}}
                                    </a>
                                </li>


                                <li class="scrollable-container media-list w-100">
                                    <a :href="notification.data.link" v-for="notification in notifications.data" @click.prevent="markAsRead(notification.id, $event)">
                                        <div class="media">
                                            <div class="media-left align-self-center">
                                                <i :class="notification.data.icon_class"></i>
                                            </div>
                                            <div class="media-body">
                                                <p class="notification-text font-small-3 text-muted" v-text="notification.data.message"></p>
                                                <small>
                                                    <time class="media-meta text-muted" :datetime="notification.created_at" v-text="dateDiffForHumans(notification.created_at)"></time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="media" v-if="!notifications.data.length">
                                        <div class="media-body">
                                            <h5 class="text-center">{{__('No unread notifications!')}}</h5>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </li>

                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label position-relative animated infinite" @click="hideMailAlert()"
                               :class="mailAnimation" href="#" data-toggle="dropdown">
                                <i class="ficon ft-monitor"> </i>
                                <span class="badge badge-pill badge-default badge-warning badge-default badge-up badge-glow" v-if="messages.total">
                                    @{{messages.total}}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">{{__('Recent Trade Chats')}}</span>
                                    </h6>
                                    <span class="notification-tag badge badge-default badge-warning float-right m-0">
                                        @{{messages.total}}
                                    </span>
                                </li>

                                <li class="scrollable-container media-list w-100">
                                    <a :href="'/home/trade/' + message.trade.token" v-for="message in messages.data">
                                        <div class="media">
                                            <div class="media-left">
                                                <span class="avatar avatar-sm rounded-circle">
                                                    <img :src="getProfileAvatar(message.user)" alt="avatar"><i></i>
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    @{{message.user.name}}
                                                    <small>(@{{message.trade.token}})</small>
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted" v-if="message.type === 'text'">
                                                    @{{message.content}}
                                                </p>
                                                <p class="notification-text font-small-3 text-muted" v-else>
                                                    {{__('File:')}} <b>@{{truncate(message.content.replace(/^.*[\\\/]/,
                                                        ''),
                                                        8)}}</b>
                                                </p>
                                                <small>
                                                    <time class="media-meta text-muted" :datetime="message.created_at" v-text="dateDiffForHumans(message.created_at)"></time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="media" v-if="!messages.data.length">
                                        <div class="media-body">
                                            <h5 class="text-center">
                                                {{__('No chat found on any active trade!')}}
                                            </h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</app-navigation>
