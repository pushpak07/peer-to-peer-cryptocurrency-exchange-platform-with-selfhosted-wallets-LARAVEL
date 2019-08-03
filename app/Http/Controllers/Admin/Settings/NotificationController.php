<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\EmailComponent;
use App\Models\NotificationTemplate;
use App\Notifications\Authentication\UserRegistered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;

class NotificationController extends Controller
{
    /**
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * NotificationController constructor.
     *
     * @param DotenvEditor $editor
     */
    public function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }

    /**
     * Show configurations page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.notification.index')->with([
            'email_component' => emailComponent(),
        ]);
    }

    /**
     * Update email components
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateComponent(Request $request)
    {
        $email_component = emailComponent();

        $email_component->fill([
            'header' => $request->header,
            'footer' => $request->footer,
        ]);

        $message = __("Your configuration has been updated!");

        $email_component->save();

        return success_response($message);
    }

    /**
     * Update notification templates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateTemplate(Request $request)
    {
        $templates = NotificationTemplate::where('name', $request->name);

        if($template = $templates->first()){

            if ($request->has('action')) {
                $template->action = json_encode($request->action);
            }

            $template->fill($request->only(['subject', 'intro_line', 'outro_line', 'message']));

            $template->save();

            return success_response(__("Your template has been updated!"));

        }else{
            return error_response(__("Template could not be found!"));
        }

    }

    /**
     * Update general configurations
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateGeneral(Request $request)
    {
        $environment = collect($this->environment())
            ->intersectByKeys($request->all());

        $rules = $environment->mapWithKeys(function ($value, $key) {
            return [$key => $value['rules']];
        });

        $this->validate($request, $rules->toArray());

        $values = $environment->map(function ($value, $key) use ($request) {
            $data = [
                'key'   => $key,
                'value' => $request->get($key)
            ];

            if (isset($value['save'])) {
                $data = [
                    'key'   => $key,
                    'value' => $value['save']($request)
                ];
            }

            return $data;
        });

        $this->editor->setKeys($values->toArray());
        $this->editor->save();

        $message = __("Your settings has been updated!");

        return success_response($message);
    }

    /**
     * Define environment properties
     *
     * @return array
     */
    private function environment()
    {
        return [
            'MAIL_DRIVER' => [
                'rules' => 'required',
            ],

            'MAIL_HOST' => [
                'rules' => 'nullable|string',
            ],

            'MAIL_PORT' => [
                'rules' => 'nullable|string',
            ],

            'MAIL_USERNAME' => [
                'rules' => 'nullable|string',
            ],

            'MAIL_PASSWORD' => [
                'rules' => 'nullable|string',
            ],

            'MAIL_ENCRYPTION' => [
                'rules' => 'nullable|string',
            ],

            'MAIL_FROM_ADDRESS' => [
                'rules' => 'nullable|email',
            ],

            'MAIL_FROM_NAME' => [
                'rules' => 'required_with:MAIL_FROM_ADDRESS|nullable|string',
            ],

            'SPARKPOST_SECRET' => [
                'rules' => 'required_if:MAIL_DRIVER,sparkpost|nullable|string',
            ],

            'SES_KEY' => [
                'rules' => 'required_if:MAIL_DRIVER,ses|nullable|string',
            ],

            'SES_SECRET' => [
                'rules' => 'required_if:MAIL_DRIVER,ses|nullable|string',
            ],

            'SES_REGION' => [
                'rules' => 'required_if:MAIL_DRIVER,ses|nullable|string',
            ],

            'MAILGUN_DOMAIN' => [
                'rules' => 'required_if:MAIL_DRIVER,mailgun|nullable|string',
            ],

            'MAILGUN_SECRET' => [
                'rules' => 'required_if:MAIL_DRIVER,mailgun|nullable|string',
            ],

            'SMS_PROVIDER' => [
                'rules' => 'required',
            ],

            'NEXMO_KEY' => [
                'rules' => 'nullable|string',
            ],

            'NEXMO_SECRET' => [
                'rules' => 'nullable|string',
            ],

            'NEXMO_PHONE' => [
                'rules' => 'nullable|string',
            ],

            'TWILIO_TOKEN' => [
                'rules' => 'nullable|string',
            ],

            'TWILIO_ID' => [
                'rules' => 'nullable|string',
            ],

            'TWILIO_NUMBER' => [
                'rules' => 'nullable|string',
            ],

            'AFRICASTALKING_USERNAME' => [
                'rules' => 'nullable|string',
            ],

            'AFRICASTALKING_KEY' => [
                'rules' => 'nullable|string',
            ],

            'AFRICASTALKING_FROM' => [
                'rules' => 'nullable|string',
            ]
        ];
    }
}
