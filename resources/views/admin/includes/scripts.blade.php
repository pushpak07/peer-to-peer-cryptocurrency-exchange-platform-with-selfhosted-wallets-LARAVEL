<script type="text/javascript">
    window.Laravel = {
        @if(env('BROADCAST_DRIVER') == "pusher")
        pusher: @json([
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'key' => env('PUSHER_APP_KEY'),
        ]),
        @endif

        locale: '{{config('app.locale')}}',

        @if(Auth::check())
        user: @json([
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
        ]),
        @endif

        broadcaster: '{{env('BROADCAST_DRIVER')}}',
    };
</script>
