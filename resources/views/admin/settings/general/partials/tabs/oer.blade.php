<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Open Exchange Rates')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            {!! Form::open(['url' => route('admin.settings.general.update'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
            <div class="form-body">
                <h4 class="form-section"><i class="ft-globe"></i> {{__('Open Exchange Rates')}}</h4>

                <div class="bs-callout-info callout-border-left mb-1 p-1">
                    <p class="card-text">
                        <b>{{__('Notice!')}}</b> {{__('This is required to perform currency conversions using the latest exchange rates. If this is not set, old exchange rates will be used.')}}
                    </p>
                </div>

                <div class="form-group row">
                    {!! Form::label('OER_KEY', 'Key', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('OER_KEY', env('OER_KEY'), ['class' => 'form-control']) !!}
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
