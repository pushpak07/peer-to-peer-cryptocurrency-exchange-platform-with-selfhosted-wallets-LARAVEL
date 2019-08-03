<?php

namespace App\Http\Controllers\Admin\Settings;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Jackiedo\DotenvEditor\DotenvEditor;
use neto737\BitGoSDK\BitGoExpress;

class GeneralController extends Controller
{
    /**
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * GeneralController constructor.
     *
     * @param DotenvEditor $editor
     * @throws \Exception
     */
    public function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }

    /**
     * Show general configuration
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.general.index');
    }

    /**
     * Update general configurations
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
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
            'APP_NAME' => [
                'rules' => 'required|string|max:13',
            ],

            'APP_DESCRIPTION' => [
                'rules' => 'nullable|string|max:160',
            ],

            'APP_KEYWORDS' => [
                'rules' => 'nullable|string|max:70',
            ],

            'APP_URL' => [
                'rules' => 'required|string|url',
            ],

            'APP_REDIRECT_HTTPS' => [
                'rules' => 'required|in:true,false',
            ],

            'APP_SHORTCUT_ICON' => [
                'rules' => 'nullable|mimes:ico|max:50',
                'save'  => function (Request $request) {
                    if ($request->has('APP_SHORTCUT_ICON')) {
                        $file = $request->file('APP_SHORTCUT_ICON');

                        $name = $file->getClientOriginalName();

                        $target = $file->move('images/uploads', $name);

                        return url($target);
                    }
                }
            ],

            'APP_LOGO_ICON' => [
                'rules' => 'nullable|mimes:png|dimensions:width=30,height=30|max:50',
                'save'  => function (Request $request) {
                    if ($request->has('APP_LOGO_ICON')) {
                        $file = $request->file('APP_LOGO_ICON');

                        $name = $file->getClientOriginalName();

                        $target = $file->move('images/uploads', $name);

                        return url($target);
                    }
                }
            ],

            'APP_LOGO_BRAND' => [
                'rules' => 'nullable|mimes:png|dimensions:width=159,height=40|max:50',
                'save'  => function (Request $request) {
                    if ($request->has('APP_LOGO_BRAND')) {
                        $file = $request->file('APP_LOGO_BRAND');

                        $name = $file->getClientOriginalName();

                        $target = $file->move('images/uploads', $name);

                        return url($target);
                    }
                }
            ],

            'NOCAPTCHA_ENABLE' => [
                'rules' => 'required|in:true,false',
            ],

            'NOCAPTCHA_SECRET' => [
                'rules' => 'nullable|required_if:NOCAPTCHA_ENABLE,true|string',
            ],

            'NOCAPTCHA_SITEKEY' => [
                'rules' => 'nullable|required_if:NOCAPTCHA_ENABLE,true|string',
            ],

            'NOCAPTCHA_TYPE' => [
                'rules' => 'nullable|required_if:NOCAPTCHA_ENABLE,true|in:v2,invisible',
            ],

            'BROADCAST_DRIVER' => [
                'rules' => 'required|in:redis,pusher',
            ],

            'PUSHER_APP_ID' => [
                'rules' => 'required_if:BROADCAST_DRIVER,pusher|nullable|string',
            ],

            'PUSHER_APP_CLUSTER' => [
                'rules' => 'required_if:BROADCAST_DRIVER,pusher|nullable|string',
            ],

            'PUSHER_APP_KEY' => [
                'rules' => 'required_if:BROADCAST_DRIVER,pusher|nullable|string',
            ],

            'PUSHER_APP_SECRET' => [
                'rules' => 'required_if:BROADCAST_DRIVER,pusher|nullable|string',
            ],

            'REDIS_HOST' => [
                'rules' => 'required_if:BROADCAST_DRIVER,redis|nullable|string',
            ],

            'REDIS_PASSWORD' => [
                'rules' => 'required_if:BROADCAST_DRIVER,redis|nullable|string',
            ],

            'REDIS_PORT' => [
                'rules' => 'required_if:BROADCAST_DRIVER,redis|nullable|string',
            ],

            'BITGO_ENV' => [
                'rules' => 'required|in:test,prod',
            ],

            'BITGO_TOKEN' => [
                'rules' => [
                    'required_with_all:BITGO_HOST,BITGO_PORT,BITGO_ENV', 'bail',
                    function ($attribute, $value, $fail) {
                        $env = request()->get('BITGO_ENV');
                        $host = request()->get('BITGO_HOST');
                        $port = request()->get('BITGO_PORT');

                        $express = new BitGoExpress(
                            $host, $port, ($env == 'prod') ? 'btc' : 'tbtc'
                        );

                        $express->accessToken = $value;

                        if ($session = $express->getSessionInfo()) {
                            if (isset($session['error'])) {
                                $fail(ucfirst($session['error']));
                                return;
                            }

                            if (!isset($session['session']['unlock'])) {
                                $fail(__('You need to generate a long-lived token!'));
                                return;
                            }
                        } else {
                            $fail(__('Unable to connect to blockchain network!'));
                            return;
                        }

                        if ($wallets = $express->listWallets()) {
                            if (isset($wallets['error'])) {
                                $fail(ucfirst($wallets['error']));
                                return;
                            }

                            if (!isset($wallets['wallets'])) {
                                $fail(__('Inappropriate connection was established!'));
                                return;
                            }
                        } else {
                            $fail(__('Server environment does not match!'));
                            return;
                        }

                    }
                ]
            ],

            'BITGO_HOST' => [
                'rules' => 'required|url',
            ],

            'BITGO_PORT' => [
                'rules' => 'required|numeric',
            ],

            'OER_KEY' => [
                'rules' => [
                    'required', 'string',
                    function ($attribute, $value, $fail) {
                        try {
                            $client = new Client();

                            $client->get("https://openexchangerates.org/api/usage.json", [
                                'query' => ['app_id' => $value]
                            ]);

                        } catch (\Exception $e) {
                            $fail(__('The API key entered was invalid!'));
                        }
                    }
                ],
            ]
        ];
    }
}
