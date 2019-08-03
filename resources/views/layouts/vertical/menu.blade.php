<app-menu inline-template>
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <a href="{{route('home.index')}}">
                        <i class="la la-home"></i>
                        <span class="menu-title">{{__('Home')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('wallet.index')}}">
                        <i class="la la-money"></i>
                        <span class="menu-title">{{__('Wallet')}}</span>
                    </a>
                </li>

                <li class="navigation-header">
                    <span>{{__('MARKETPLACE')}}</span>
                </li>

                <li class="nav-item">
                    <a href="{{route('market.buy-coin.index')}}">
                        <i class="la la-shopping-cart"></i>
                        <span class="menu-title">{{__('Buy Coin')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('market.sell-coin.index')}}">
                        <i class="la la-dollar"></i>
                        <span class="menu-title">{{__('Sell Coin')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('market.create-offer.sell')}}">
                        <i class="la la-cart-plus"></i>
                        <span class="menu-title">{{__('Create Offer')}}</span>
                    </a>
                </li>

                <li class="navigation-header">
                    <span>{{__('MY PROFILE')}}</span>
                </li>

                <li class="nav-item">
                    <a href="{{route('profile.contacts.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-tty"></i>
                        <span class="menu-title">{{__('Contacts')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('profile.trades.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-briefcase"></i>
                        <span class="menu-title">{{__('My Trades')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('profile.notifications.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-bell"></i>
                        <span class="menu-title">{{__('Notifications')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('profile.settings.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-gear"></i>
                        <span class="menu-title">{{__('Settings')}}</span>
                    </a>
                </li>

                @can('resolve trade dispute')
                    <li class="navigation-header">
                        <span>{{__('MODERATION')}}</span>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('moderation.trades.index')}}">
                            <i class="la la-briefcase"></i> <span class="menu-title">{{__('Trades')}}</span>

                            @if($dispute = \App\Models\Trade::where('status', 'dispute')->count())
                                <span class="badge badge-pill badge-default badge-warning float-right badge-glow">
                                    {{$dispute}} {{__('dispute')}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</app-menu>

