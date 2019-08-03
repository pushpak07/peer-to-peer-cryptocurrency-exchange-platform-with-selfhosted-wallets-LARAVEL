<span><b>{{$data->payment_method}}</b> - {{$data->label}}</span>
<br/>
@foreach($data->tags as $tag)
    <span class="badge border-left-primary border-right-primary badge-sm round badge-striped">
        {{$tag}}
    </span>
@endforeach
