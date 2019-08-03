<app-admin-footer inline-template>
    <footer class="footer footer-fixed footer-light navbar-border navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">
                {{__('Copyright')}} &copy; 2018 | {{config('app.name')}}
            </span>
            <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">
                {{app(\PragmaRX\Version\Package\Version::class)->version()}}
            </span>
        </p>
    </footer>
</app-admin-footer>

