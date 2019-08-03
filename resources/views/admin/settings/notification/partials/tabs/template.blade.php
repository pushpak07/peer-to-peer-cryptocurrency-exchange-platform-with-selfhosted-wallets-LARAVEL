@php $template = $configuration->template() @endphp

<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Template')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            {!! Form::open(['url' => route('admin.settings.notification.update-template'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
            <div class="form-body">
                {!! Form::hidden('name', $template->name) !!}

                <div class="form-group row">
                    {!! Form::label('subject', 'Subject', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('subject', $template->subject, ['class' => 'form-control']) !!}
                    </div>
                </div>

                @if($template->hasMailChannel())
                    <h4 class="form-section"><i class="ft-mail"></i> {{__('EMAIL')}}</h4>

                    <div class="form-group">
                        {!! Form::label('intro_line', 'Intro Line') !!}
                        <tinymce id="intro_line_{{$template->name}}" name="intro_line" value="{{$template->intro_line}}"></tinymce>
                    </div>

                    @if($action = $template->action())
                        <div class="form-group">
                            {!! Form::label('action', 'Action Button') !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ft-globe"></i></span>
                                            </div>
                                            {!! Form::text('action[url]', $action['url'], ['class' => 'form-control', 'placeholder' => 'Button Link']) !!}
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ft-file-text"></i></span>
                                            </div>
                                            {!! Form::text('action[text]', $action['text'], ['class' => 'form-control', 'placeholder' => 'Button Text']) !!}
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        {!! Form::label('outro_line', 'Outro Line') !!}
                        <tinymce id="outro_line_{{$template->name}}" name="outro_line" value="{{$template->outro_line}}"></tinymce>
                    </div>
                @endif


                @if($template->hasDatabaseChannel() || $template->hasSmsChannel())
                    <h4 class="form-section"><i class="ft-bell"></i> {{__('NOTIFICATION/SMS')}}</h4>

                    <div class="form-group">
                        {!! Form::label('message', 'Message') !!}
                        {!! Form::textarea('message', $template->message, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                @endif

                <div class="bs-callout-warning callout-border-left mt-1 p-1" role="alert">
                    <h4 class="alert-heading mb-2">{{__('Parameters')}}!</h4>
                    <p>
                        @foreach($configuration->getParameters() as $key => $value)
                            <code><b>{{$key}}</b></code> - {{$value}},
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="form-actions right">
                <button type="submit" class="btn ladda-button btn-success">
                    <i class="la la-check-square-o"></i> {{__('Save')}}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
