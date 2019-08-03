<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('BitGo')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            {!! Form::open(['url' => route('admin.settings.general.update'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
            <div class="form-body">
                <h4 class="form-section"><i class="ft-wifi"></i> {{__('BitGo')}}</h4>

                <div class="form-group row">
                    {!! Form::label('BITGO_ENV', 'Environment', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('BITGO_ENV', ['test' => 'Test', 'prod' => 'Production'], env('BITGO_ENV'), ['is' => 'select2', 'class' => 'form-control']) !!}
                        <small class="help-block">
                            {{__('Ensure that you have started the server with the right environment.')}}
                        </small>
                    </div>
                </div>


                <div class="form-group row">
                    {!! Form::label('BITGO_TOKEN', 'Token', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('BITGO_TOKEN', env('BITGO_TOKEN'), ['class' => 'form-control']) !!}
                        <small class="help-block">
                            {{__('A long-lived token is required. Refer to the documentation')}}
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('BITGO_HOST', 'Host', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('BITGO_HOST', env('BITGO_HOST'), ['class' => 'form-control']) !!}
                        <small class="help-block">
                            {{__('With protocol, http or https')}}
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('BITGO_PORT', 'Port', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('BITGO_PORT', env('BITGO_PORT'), ['class' => 'form-control']) !!}
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
