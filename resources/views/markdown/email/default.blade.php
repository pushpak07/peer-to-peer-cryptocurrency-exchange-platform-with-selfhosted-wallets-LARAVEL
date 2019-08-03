@extends('markdown.layouts.master')

@section('body')
{!! __($template->intro_line, $replacement) !!}

@if(isset($action['url']) && isset($action['url']))
@component('mail::button', ['url' => $action['url']])
    {!! (isset($action['text']) && $action['text'])? $action['text']: __('Go!') !!}
@endcomponent
@endif

{!! __($template->outro_line, $replacement) !!}

@endsection
