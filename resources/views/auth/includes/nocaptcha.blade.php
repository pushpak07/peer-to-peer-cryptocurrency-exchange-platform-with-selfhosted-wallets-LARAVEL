@php
	$status = config()->get('services.nocaptcha.enable');
	$type = strtolower(config()->get('services.nocaptcha.type'));
	$attribute = $button['attributes'];
@endphp

@if($status)
	@if($type == 'v2')
		<div class="text-center mb-1">
			{!! htmlFormSnippet() !!}
		</div>
		
		{!! Form::button($button['title'], $attribute); !!}
	@endif
	
	@if($type == 'invisible')
		{!! htmlFormButton($button['title'], $attribute) !!}
	@endif
	
	@push('data')
		{!! htmlScriptTagJsApi('auth-form') !!}
	@endpush
@else
	{!! Form::button($button['title'], $attribute); !!}
@endif


