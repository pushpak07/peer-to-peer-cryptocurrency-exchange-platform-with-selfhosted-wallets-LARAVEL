@foreach(collect($content)->chunk(2) as $chunk)
    <div class="row">
        @foreach($chunk as $key => $data)
            <div class="col-sm-6">
                @switch($data['type'])
                    @case('text')
                    <div class="form-group {{ $errors->has($key) ? ' has-error ' : '' }}">
                        {!! Form::label($key, $data['label']) !!}
                        {!! Form::text($key, $data['value'], ['placeholder' => $data['placeholder'], 'class' => 'form-control', 'required']) !!}
                        @if($data['hint'])
                            <span class="help-block">{{ $data['hint'] }}</span>
                        @endif
                    </div>
                    @break

                    @case('select')
                    <div class="form-group">
                        {!! Form::label($key, $data['label']) !!}
                        {!! Form::select($key, $data['options'], $data['value'], ['placeholder' => $data['placeholder'],  'class' => 'form-control', 'required']) !!}
                        @if($data['hint'])
                            <span class="help-block">{{ $data['hint'] }}</span>
                        @endif
                    </div>
                    @break

                    @case('textarea')
                    <div class="form-group">
                        {!! Form::label($key, $data['label']) !!}
                        {!! Form::textarea($key, $data['value'], ['placeholder' => $data['placeholder'], 'class' => 'form-control', 'required', 'rows' => 4]) !!}
                        @if($data['hint'])
                            <span class="help-block">{{ $data['hint'] }}</span>
                        @endif
                    </div>
                    @break
                @endswitch
            </div>
        @endforeach
    </div>
@endforeach

