<div class="card">
    <div class="card-head">
        <div class="card-header">
            <h4 class="card-title">{{__('General')}}</h4>
        </div>
    </div>

    <div class="card-content">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="environment-tab" data-toggle="tab" aria-controls="environment"
                       href="#environment" aria-expanded="true">{{__('Environment')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="component-tab" aria-controls="component"
                       href="#component" aria-expanded="false" data-toggle="tab">
                        {{__('Components')}}
                    </a>
                </li>
            </ul>

            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="environment" aria-expanded="true" aria-labelledby="environment-tab">
                    <div class="card-text">
                        <p>
                            {{__('Please note that this settings will be written into the')}}
                            <code>.env</code> {{__('file of this platform! You are strongly advised to back it up before saving the changes.')}}
                            {{__('You may also refer to the documentation to discover how to obtain your preferred mail driver api keys')}}
                        </p>
                    </div>
                    {!! Form::open(['url' => route('admin.settings.notification.update-general'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
                    <div class="form-body">
                        <h4 class="form-section"><i class="ft-mail"></i> {{__('EMAIL')}}</h4>
                        <div class="form-group row">
                            {!! Form::label('MAIL_DRIVER', 'Driver', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::select('MAIL_DRIVER', get_mail_drivers(), env('MAIL_DRIVER'), ['is' => 'select2', 'class' => 'form-control', 'v-model' => 'form.settings.mail_driver']) !!}
                            </div>
                        </div>

                        <div v-if="form.settings.mail_driver == 'mailgun'">
                            <div class="form-group row">
                                {!! Form::label('MAILGUN_DOMAIN', 'Mailgun Domain', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAILGUN_DOMAIN', env('MAILGUN_DOMAIN'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAILGUN_SECRET', 'Mailgun Secret', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAILGUN_SECRET', env('MAILGUN_SECRET'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div v-if="form.settings.mail_driver == 'ses'">
                            <div class="form-group row">
                                {!! Form::label('SES_KEY', 'SES Key', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('SES_KEY', env('SES_KEY'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('SES_SECRET', 'SES Secret', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('SES_SECRET', env('SES_SECRET'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('SES_REGION', 'SES Region', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('SES_REGION', env('SES_REGION'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div v-if="form.settings.mail_driver == 'sparkpost'">
                            <div class="form-group row">
                                {!! Form::label('SPARKPOST_SECRET', 'Sparkpost Secret', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('SPARKPOST_SECRET', env('SPARKPOST_SECRET'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div v-if="form.settings.mail_driver == 'smtp'">
                            <div class="form-group row">
                                {!! Form::label('MAIL_HOST', 'Mail Host', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_HOST', env('MAIL_HOST'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAIL_PORT', 'Mail Port', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_PORT', env('MAIL_PORT'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAIL_USERNAME', 'Mail Username', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_USERNAME', env('MAIL_USERNAME'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAIL_PASSWORD', 'Mail Password', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_PASSWORD', env('MAIL_PASSWORD'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAIL_PASSWORD', 'Mail Password', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_PASSWORD', env('MAIL_PASSWORD'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('MAIL_ENCRYPTION', 'Mail Encryption', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('MAIL_FROM_ADDRESS', 'Mail From Address', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::email('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('MAIL_FROM_NAME', 'Mail From Name', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::text('MAIL_FROM_NAME', env('MAIL_FROM_NAME'), ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <h4 class="form-section"><i class="ft-smartphone"></i> {{__('MOBILE SMS')}}</h4>

                        <div class="form-group row">
                            {!! Form::label('SMS_PROVIDER', 'Provider', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::select('SMS_PROVIDER', get_sms_providers(), env('SMS_PROVIDER'), ['is' => 'select2', 'class' => 'form-control', 'v-model' => 'form.settings.sms_provider']) !!}
                            </div>
                        </div>

                        <div v-if="form.settings.sms_provider == 'twilio'">
                            <div class="form-group row">
                                {!! Form::label('TWILIO_TOKEN', 'Twilio Token', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('TWILIO_TOKEN', env('TWILIO_TOKEN'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('TWILIO_ID', 'Twilio Id', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('TWILIO_ID', env('TWILIO_ID'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('TWILIO_NUMBER', 'Twilio Number', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('TWILIO_NUMBER', env('TWILIO_NUMBER'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="form.settings.sms_provider == 'nexmo'">
                            <div class="form-group row">
                                {!! Form::label('NEXMO_KEY', 'Nexmo Key', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('NEXMO_KEY', env('NEXMO_KEY'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('NEXMO_SECRET', 'Nexmo Secret', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('NEXMO_SECRET', env('NEXMO_SECRET'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('NEXMO_PHONE', 'Nexmo Phone', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('NEXMO_PHONE', env('NEXMO_PHONE'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div v-if="form.settings.sms_provider == 'africastalking'">
                            <div class="form-group row">
                                {!! Form::label('AFRICASTALKING_USERNAME', 'AfricasTalking Username', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('AFRICASTALKING_USERNAME', env('AFRICASTALKING_USERNAME'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('AFRICASTALKING_KEY', 'AfricasTalking Secret', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('AFRICASTALKING_KEY', env('AFRICASTALKING_KEY'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group row">
                                {!! Form::label('AFRICASTALKING_FROM', 'AfricasTalking From', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('AFRICASTALKING_FROM', env('AFRICASTALKING_FROM'), ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions right">
                        <button type="submit" class="btn ladda-button btn-success">
                            <i class="la la-check-square-o"></i> {{__('Save')}}
                        </button>
                    </div>
                    {!! Form::close() !!}

                </div>
                <div class="tab-pane" id="component" aria-labelledby="component-tab">
                    {!! Form::open(['url' => route('admin.settings.notification.update-component'), 'class' => 'form form-horizontal', 'method' => 'POST']) !!}
                    <div class="form-body">
                        <h4 class="form-section">
                            <i class="ft-mail"></i> {{__('EMAIL')}}
                        </h4>

                        <div class="form-group">
                            {!! Form::label('header', 'Header') !!}
                            <tinymce id="header" name="header" value="{{$email_component->header}}"></tinymce>
                        </div>

                        <div class="form-group">
                            {!! Form::label('footer', 'Footer') !!}
                            <tinymce id="footer" name="footer" value="{{$email_component->footer}}"></tinymce>
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
    </div>
</div>
