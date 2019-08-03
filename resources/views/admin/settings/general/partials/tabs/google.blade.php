<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Google')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            {!! Form::open(['url' => route('admin.settings.general.update'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
            <div class="form-body">
                <h4 class="form-section"><i class="ft-shield"></i> {{__('RECAPTCHA')}}</h4>

                <div class="form-group row">
                    {!! Form::label('NOCAPTCHA_ENABLE', 'Enable', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('NOCAPTCHA_ENABLE', ['true' => 'Yes', 'false' => 'No'], env('NOCAPTCHA_ENABLE'), ['is' => 'select2', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('NOCAPTCHA_TYPE', 'Enable', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('NOCAPTCHA_TYPE', ['v2' => 'V2', 'invisible' => 'Invisible'], env('NOCAPTCHA_TYPE'), ['is' => 'select2', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('NOCAPTCHA_SECRET', 'Secret', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('NOCAPTCHA_SECRET', env('NOCAPTCHA_SECRET'), ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('NOCAPTCHA_SITEKEY', 'Site Key', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('NOCAPTCHA_SITEKEY', env('NOCAPTCHA_SITEKEY'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="form-actions right">
                <button type="submit" class="btn ladda-button btn-success">
                    {{__('UPDATE')}}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
