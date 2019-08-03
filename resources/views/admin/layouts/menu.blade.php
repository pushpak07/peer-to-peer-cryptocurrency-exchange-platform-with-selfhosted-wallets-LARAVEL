<app-admin-menu inline-template>
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li>
                    <a href="{{route('admin.home.index')}}">
                        <i class="la la-home"></i>
                        <span class="menu-title">{{__('Home')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.earnings.index')}}">
                        <i class="la la-dollar"></i>
                        <span class="menu-title">{{__('Earnings')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.users.index')}}">
                        <i class="la la-users"></i>
                        <span class="menu-title">{{__('Users')}}</span>
                    </a>
                </li>

                <li class="navigation-header">
                    <span>{{__('SETTINGS')}}</span>
                </li>

                <li>
                    <a href="{{route('admin.settings.general.index')}}">
                        <i class="la la-globe"></i>
                        <span class="menu-title">{{__('General')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.settings.notification.index')}}">
                        <i class="la la-bell"></i>
                        <span class="menu-title">{{__('Notifications')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.settings.offer.index')}}">
                        <i class="la la-shopping-cart"></i>
                        <span class="menu-title">{{__('Offer')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.settings.transaction.index')}}">
                        <i class="la la-bolt"></i>
                        <span class="menu-title">{{__('Transaction')}}</span>
                    </a>
                </li>

                <li class="navigation-header">
                    <span>{{__('PLATFORM')}}</span>
                </li>

                <li>
                    <a href="{{route('admin.platform.customize.index')}}">
                        <i class="la la-paint-brush"></i>
                        <span class="menu-title">{{__('Customize')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.platform.translation.index')}}">
                        <i class="la la-language"></i>
                        <span class="menu-title">{{__('Translation')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.platform.integration.index')}}">
                        <i class="la la-barcode"></i>
                        <span class="menu-title">{{__('Integration')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.platform.license.index')}}">
                        <i class="la la-certificate"></i>
                        <span class="menu-title">{{__('License')}}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</app-admin-menu>

