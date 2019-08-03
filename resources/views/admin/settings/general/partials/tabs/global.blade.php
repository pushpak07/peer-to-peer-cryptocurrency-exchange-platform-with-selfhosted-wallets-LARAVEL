<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('Global')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            {!! Form::open(['url' => route('admin.settings.general.update'), 'class' => 'form form-horizontal', 'method' => 'POST', 'files' => true]) !!}
            <div class="form-body">
                <h4 class="form-section"><i class="ft-globe"></i> {{__('APPLICATION')}}</h4>

                <div class="form-group row">
                    {!! Form::label('APP_NAME', 'Name', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('APP_NAME', env('APP_NAME'), ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('APP_DESCRIPTION', 'Description', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('APP_DESCRIPTION', env('APP_DESCRIPTION'), ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('APP_KEYWORDS', 'Keywords', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('APP_KEYWORDS', env('APP_KEYWORDS'), ['class' => 'form-control']) !!}
                    </div>
                </div>


                <div class="form-group row">
                    {!! Form::label('APP_URL', 'Url', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend"> <span class="input-group-text"><i class="ft-link"></i></span> </div>

                            {!! Form::text('APP_URL', env('APP_URL'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('APP_REDIRECT_HTTPS', 'Force SSL', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('APP_REDIRECT_HTTPS', ['true' => 'Yes', 'false' => 'No'], env('APP_REDIRECT_HTTPS'), ['is' => 'select2', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('APP_SHORTCUT_ICON', 'Shortcut Icon', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-8">
                                <input type="file" class="fileselect" name="APP_SHORTCUT_ICON">

                                <p class="text-left">
                                    <small class="text-muted">
                                        {{__('Hint: You should use online favicon generator.')}}
                                    </small>
                                </p>
                            </div>
                            <div class="col-4 text-center">
                                @if(env('APP_SHORTCUT_ICON'))
                                    <img src="{{env('APP_SHORTCUT_ICON')}}" class="img-bordered img-responsive"/>
                                @else
                                    <img src="{{asset('/images/icon/favicon.ico')}}" class="img-bordered img-responsive"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>



                <div class="form-group row">
                    {!! Form::label('APP_LOGO_BRAND', 'Logo Brand', ['class' => 'col-md-3']) !!}

                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-8">
                                <input type="file" class="fileselect" name="APP_LOGO_BRAND">

                                <p class="text-left">
                                    <small class="text-muted">
                                        {{__('Requirement:')}} <code>159</code> x <code>40</code> (png)
                                    </small>
                                </p>
                            </div>
                            <div class="col-4 text-center">
                                @if(env('APP_LOGO_BRAND'))
                                    <img src="{{env('APP_LOGO_BRAND')}}" class="img-bordered img-responsive"/>
                                @else
                                    <img src="{{asset('/images/logo/logo-dark.png')}}" class="img-bordered img-responsive"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('APP_LOGO_ICON', 'Logo Icon', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-8">
                                <input type="file" class="fileselect" name="APP_LOGO_ICON">

                                <p class="text-left">
                                    <small class="text-muted">
                                        {{__('Requirement:')}} <code>30</code> x <code>30</code> (png)
                                    </small>
                                </p>
                            </div>
                            <div class="col-4 text-center">
                                @if(env('APP_LOGO_ICON'))
                                    <img src="{{env('APP_LOGO_ICON')}}" class="img-bordered img-responsive"/>
                                @else
                                    <img src="{{asset('/images/icon/logo-sm.png')}}" class="img-bordered img-responsive"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions right">
                <button type="submit" class="btn ladda-button btn-success">
                    {{__('UPDATE')}}
                </button>
            </div>
            {!! Form::close() !!}

            {!! Form::open(['url' => route('admin.settings.general.update'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
            <div class="form-body">
                <h4 class="form-section"><i class="ft-server"></i> {{__('BROADCAST')}}</h4>

                <div class="form-group row">
                    {!! Form::label('BROADCAST_DRIVER', 'Driver', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('BROADCAST_DRIVER', get_broadcast_drivers(), env('BROADCAST_DRIVER'), ['is' => 'select2', 'class' => 'form-control', 'v-model' => 'form.settings.broadcast_driver']) !!}
                    </div>
                </div>

                <div v-if="form.settings.broadcast_driver === 'pusher'">
                    <div class="form-group row">
                        {!! Form::label('PUSHER_APP_ID', 'Id', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('PUSHER_APP_ID', env('PUSHER_APP_ID'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('PUSHER_APP_CLUSTER', 'Cluster', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('PUSHER_APP_CLUSTER', env('PUSHER_APP_CLUSTER'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('PUSHER_APP_KEY', 'Key', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('PUSHER_APP_KEY', env('PUSHER_APP_KEY'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('PUSHER_APP_SECRET', 'Secret', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('PUSHER_APP_SECRET', env('PUSHER_APP_SECRET'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div v-if="form.settings.broadcast_driver === 'redis'">
                    <div class="form-group row">
                        {!! Form::label('REDIS_HOST', 'Host', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('REDIS_HOST', env('REDIS_HOST'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('REDIS_PASSWORD', 'Password', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('REDIS_PASSWORD', env('REDIS_PASSWORD'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('REDIS_PORT', 'Port', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('REDIS_PORT', env('REDIS_PORT'), ['class' => 'form-control']) !!}
                        </div>
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
