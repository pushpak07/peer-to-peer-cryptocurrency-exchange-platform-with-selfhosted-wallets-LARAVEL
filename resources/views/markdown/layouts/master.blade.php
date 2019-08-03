@component('mail::layout')

@php $component = emailComponent(); @endphp

@if($component->header)
	@slot('header')
		@php $url = config('app.url'); @endphp
		@component('mail::header', ['url' => $url])
		{!! $component->header !!}
		@endcomponent
	@endslot
@endif

@yield('body')

@if($component->footer)
	@slot('footer')
		@component('mail::footer')
		{!! $component->footer !!}
		@endcomponent
	@endslot
@endif

@endcomponent
