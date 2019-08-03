@if($messages = session()->get('status'))
    @if(!is_array($messages))
        {!! HTML::alert($messages, 'primary') !!}
    @else
        @foreach($messages as $message)
            {!! HTML::alert($message, 'primary') !!}
        @endforeach
    @endif
@endif

@if($messages = session()->get('error'))
    @if(!is_array($messages))
        {!! HTML::alert($messages, 'danger') !!}
    @else
        @foreach($messages as $message)
            {!! HTML::alert($message, 'danger') !!}
        @endforeach
    @endif
@endif

@if($errors->any())
    @foreach($errors->all() as $error)
        {!! HTML::alert($error, 'danger') !!}
    @endforeach
@endif

@if($messages = session()->get('success'))
    @if(!is_array($messages))
        {!! HTML::alert($messages, 'success') !!}
    @else
        @foreach($messages as $message)
            {!! HTML::alert($message, 'success') !!}
        @endforeach
    @endif
@endif

@if($messages = session()->get('warning'))
    @if(!is_array($messages))
        {!! HTML::alert($messages, 'warning') !!}
    @else
        @foreach($messages as $message)
            {!! HTML::alert($message, 'warning') !!}
        @endforeach
    @endif
@endif

