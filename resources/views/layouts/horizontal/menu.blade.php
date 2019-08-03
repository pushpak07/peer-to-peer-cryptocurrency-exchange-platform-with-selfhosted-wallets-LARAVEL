<app-menu inline-template>
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-dark navbar-without-dd-arrow navbar-shadow" role="navigation" data-menu="menu-wrapper">
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home.index')}}">
                        <i class="la la-home"></i>
                        <span class="menu-title">{{__('Home')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('wallet.index')}}">
                        <i class="la la-money"></i>
                        <span class="menu-title">{{__('Wallet')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('market.buy-coin.index')}}">
                        <i class="la la-shopping-cart"></i>
                        <span class="menu-title">{{__('Buy Coin')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('market.sell-coin.index')}}">
                        <i class="la la-dollar"></i>
                        <span class="menu-title">{{__('Sell Coin')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('market.create-offer.sell')}}">
                        <i class="la la-cart-plus"></i>
                        <span class="menu-title">{{__('Create Offer')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.contacts.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-tty"></i>
                        <span class="menu-title">{{__('Contacts')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.trades.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-briefcase"></i>
                        <span class="menu-title">{{__('My Trades')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.notifications.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-bell"></i>
                        <span class="menu-title">{{__('Notifications')}}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.settings.index', ['user' => Auth::user()->name])}}">
                        <i class="la la-gear"></i>
                        <span class="menu-title">{{__('Settings')}}</span>
                    </a>
                </li>

                @can('resolve trade dispute')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('moderation.trades.index')}}">
                            <i class="la la-briefcase"></i>

                            <span class="menu-title">{{__('Trades')}}</span>

                            @if($dispute = \App\Models\Trade::where('status', 'dispute')->count())
                                <span class="badge badge-pill badge-default badge-warning badge-glow">
                                    {{$dispute}}
                                </span>
                            @endif
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</app-menu>

