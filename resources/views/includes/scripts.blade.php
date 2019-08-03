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

	window.tableLanguage = {
		"aria": {
			"sortAscending": ": {{__('activate to sort column ascending')}}",
			"sortDescending": ": {{__('activate to sort column descending')}}"
		},
		"emptyTable": "{{__('No data available in table')}}",
		"info": "{{__('Showing').' _START_ '.__('to').' _END_ '.__('of').' _TOTAL_ '.__('entries')}}",
		"infoEmpty": "{{__('No entries found')}}",
		"infoFiltered": "{{'(filtered1 '.__('from').' _MAX_ '.__('total entries').')'}}",
		"lengthMenu": "{{'_MENU_ '.__('entries')}}",
		"search": "{{__('Search:')}}",
		"zeroRecords": "{{__('No matching records found')}}"
	}
</script>
