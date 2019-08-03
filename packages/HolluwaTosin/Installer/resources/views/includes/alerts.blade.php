@if($errors->any())
	<div class="alert alert-danger">
		<button class="close" data-close="alert"></button>
		<ul>
			@foreach($errors->all() as $error)
				<li> {{$error}}</li>
			@endforeach
		</ul>
	</div>
@endif

@if(session()->has('error'))
	<div class="alert alert-danger">
		<button class="close" data-close="alert"></button>
		<span>{{session()->get('error')}}</span>
	</div>
@endif

@if(session()->has('message'))
	<div class="alert alert-info">
		<button class="close" data-close="alert"></button>
		<span>{{session()->get('message')}}</span>
	</div>
@endif
