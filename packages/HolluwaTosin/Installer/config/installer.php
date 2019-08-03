<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Details
    |--------------------------------------------------------------------------
    */

    'name' => 'CryptEx - Ultimate Peer to Peer Cryptocurrency Exchange Platform',

    'link' => 'https://codecanyon.net/item/cryptex-ultimate-peer-to-peer-cryptocurrency-exchange-platform-with-selfhosted-wallets/22764015',

    'documentation' => 'http://products.oluwatosin.me/cryptoexchange/docs',

    'thumbnail' => 'http://oluwatosin.me/cdn/images/cryptoexchange/logo.png',

    /*
    |--------------------------------------------------------------------------
    | Author Details
    |--------------------------------------------------------------------------
    */
    'author'    => [
        'name' => 'HolluwaTosin360',

        'portfolio' => 'https://codecanyon.net/user/holluwatosin360/portfolio',

        'avatar' => 'http://oluwatosin.me/avatar.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | License Endpoint
    |--------------------------------------------------------------------------
    */
    'endpoint'  => 'http://oluwatosin.herokuapp.com/api/licenses',

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */

    'core' => [
        'minPhpVersion' => '7.1.0'
    ],

    'requirements'     => [
        'php'    => [
            'mysqli',
            'gmp',
            'bcmath',
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'JSON',
            'cURL',
            'gd',
            'fileinfo',
            'zip'
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions'      => [
        'storage/framework/' => '775',
        'storage/logs/'      => '775',
        'bootstrap/cache/'   => '775'
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Form Wizard Validation Rules & Messages
    |--------------------------------------------------------------------------
    |
    | This are the default form field validation rules. Available Rules:
    | https://laravel.com/docs/5.4/validation#available-validation-rules
    |
    */
    'environment'      => [
        'app' => [
            'APP_NAME' => [
                'label'       => 'Application Name',
                'hint'        => 'Set your website name. Make it short and precise',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:13'
            ],

            'APP_TIMEZONE' => [
                'label'       => 'Default Timezone',
                'hint'        => 'This is set as users default timezone',
                'type'        => 'select',
                'options'     => getTimeZones(),
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],

            'APP_URL' => [
                'label'       => 'Application URL',
                'hint'        => 'Set your website link. Ensure this is an active domain',
                'type'        => 'text',
                'placeholder' => '(e.g http:// or https://example.com/)',
                'rules'       => 'required|url'
            ],

            'APP_REDIRECT_HTTPS' => [
                'label'       => 'Force SSL',
                'hint'        => 'This will force your domain to be redirected to https',
                'type'        => 'select',
                'options'     => [
                    'true'  => 'Yes',
                    'false' => 'No'
                ],
                'placeholder' => 'Select',
                'rules'       => 'required|in:true,false'
            ],
        ],

        'db' => [
            'DB_HOST'     => [
                'label'       => 'Database Host',
                'hint'        => 'Leave as localhost if your database is on the same server as this script.',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],
            'DB_PORT'     => [
                'label'       => 'Database Port',
                'hint'        => 'This is usually 3306. Please specify if it is otherwise.',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|numeric'
            ],
            'DB_DATABASE' => [
                'label'       => 'Database Name',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],
            'DB_USERNAME' => [
                'label'       => 'Database Username',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],
            'DB_PASSWORD' => [
                'label'       => 'Database Password',
                'hint'        => 'The password to your database server.',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],
        ],

        'broadcast' => [
            'PUSHER_APP_ID' => [
                'label'       => 'Pusher ID',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],

            'PUSHER_APP_KEY' => [
                'label'       => 'Pusher Key',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],

            'PUSHER_APP_SECRET' => [
                'label'       => 'Pusher Secret',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],

            'PUSHER_APP_CLUSTER' => [
                'label'       => 'Pusher Cluster',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string'
            ],
        ],

        'mail' => [
            'MAIL_DRIVER'     => [
                'label'       => 'Mail Driver',
                'hint'        => 'Leave as sendmail if you want to use your server mailing system.',
                'type'        => 'select',
                'options'     => [
                    'smtp'     => 'SMTP',
                    'sendmail' => 'SENDMAIL'
                ],
                'placeholder' => '',
                'rules'       => 'required'
            ],
            'MAIL_HOST'       => [
                'label'       => 'Mail Host',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],
            'MAIL_PORT'       => [
                'label'       => 'Mail Port',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],
            'MAIL_USERNAME'   => [
                'label'       => 'Mail Username',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],
            'MAIL_PASSWORD'   => [
                'label'       => 'Mail Password',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ],
            'MAIL_ENCRYPTION' => [
                'label'       => 'Mail Encryption',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|string|max:50'
            ]
        ],

        'extras' => [
            'BITGO_ENV'   => [
                'label'       => 'BitGo Env',
                'hint'        => '',
                'type'        => 'select',
                'options'     => [
                    'test' => 'Test',
                    'prod' => 'Production'
                ],
                'placeholder' => '',
                'rules'       => 'required|in:test,prod'
            ],
            'BITGO_TOKEN' => [
                'label'       => 'BitGo Token',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => [
                    'required_with_all:BITGO_HOST,BITGO_PORT,BITGO_ENV', 'bail',
                    function ($attribute, $value, $fail) {
                        $env = request()->get('BITGO_ENV');
                        $host = request()->get('BITGO_HOST');
                        $port = request()->get('BITGO_PORT');

                        $express = new \neto737\BitGoSDK\BitGoExpress(
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
            'BITGO_HOST'  => [
                'label'       => 'BitGo Host',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|url'
            ],
            'BITGO_PORT'  => [
                'label'       => 'BitGo Port',
                'hint'        => '',
                'type'        => 'text',
                'placeholder' => '',
                'rules'       => 'required|numeric'
            ],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Installed Middlware Options
    |--------------------------------------------------------------------------
    | Different available status switch configuration for the
    | canInstall middleware located in `CanInstall.php`.
    |
    */
    'installed_action' => [
        'default' => 'abort',

        'options' => [
            'abort' => [
                'type' => '404',
            ],

            'route' => [
                'name' => '',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Updater Enabled
    |--------------------------------------------------------------------------
    | Can the application run the '/update' route with the migrations.
    | The default option is set to False if none is present.
    | Boolean value
    |
    */
    'enabled_update'   => true,
];
