<?php

namespace HolluwaTosin\Installer\Controllers;

use App\Http\Controllers\Controller;

use HolluwaTosin\Installer\Helpers\EnvironmentManager;
use HolluwaTosin\Installer\Helpers\PermissionsChecker;
use HolluwaTosin\Installer\Helpers\Traits\MigrationsHelper;
use HolluwaTosin\Installer\Installer;
use HolluwaTosin\Installer\PurchaseDetails;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use HolluwaTosin\Installer\Helpers\RequirementsChecker;
use Validator;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InstallController extends Controller
{
    use MigrationsHelper;

    /**
     * @var EnvironmentManager
     */
    protected $environment;

    /**
     * @var Installer
     */
    protected $installer;

    /**
     * InstallController constructor.
     *
     * @param EnvironmentManager $environment
     * @param Cache $cache
     */
    public function __construct(EnvironmentManager $environment)
    {
        $this->installer = resolve('installer');
        $this->environment = $environment;
    }

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('installer::overview.index');
    }

    /**
     * Validate Purchase and proceed with installation.
     *
     * @param Request $request
     * @param Cache $cache
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Cache $cache)
    {
        $validator = Validator::make($request->all(), [
            'verification' => 'required|min:10',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        try{
            $code = $request->get('verification');

            $details = $this->installer->check($code);

            $key = $this->installer->prefix . 'code';

            if(!($details instanceof PurchaseDetails)){
                if(is_array($details) && isset($details['error'])){
                    $validator->getMessageBag()->add(
                        'verification', $details['message']
                    );

                }else{
                    $validator->getMessageBag()->add(
                        'verification', 'Something went wrong!'
                    );
                }

                return redirect()->back()->withErrors($validator);

            }else{
                session()->put($key, $code);
            }
        }catch(\Exception $e){
            $validator->getMessageBag()->add('verification', $e->getMessage());

            return redirect()->back()->withErrors($validator);
        }

        return redirect()->route('Installer::overview.requirements');
    }

    /**
     * Show Requirements
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requirements()
    {
        $checker = new RequirementsChecker();

        return view('installer::overview.requirements', [
            'php' => $checker->checkPHPversion(),
            'requirements' => $checker->check()
        ]);
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $checker = new RequirementsChecker();

        if($checker->pass()){
            $checker = new PermissionsChecker();

            return view('installer::overview.permissions', [
                'permissions' => $checker->check()
            ]);
        }else{
            return redirect()->route(
                'Installer::overview.requirements'
            );
        }
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environment()
    {
        $checker = new PermissionsChecker();

        if($checker->pass()){
            return view('installer::overview.environment', [
                'content' => $this->environment->getContent()
            ]);
        }else{
            return redirect()->route(
                'Installer::overview.permissions'
            );
        }
    }

    /**
     * Save environment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveEnvironment(Request $request)
    {
        $rules = array();

        foreach ($this->environment->getKeyPairs() as $key => $value){
            $rules[$key] = $value['rules'];
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        if($request->has(['DB_DATABASE', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT'])){
            try{
                // Verify Database Connection...
                $db_database = $request->get('DB_DATABASE');
                $db_host = $request->get('DB_HOST');
                $db_username = $request->get('DB_USERNAME');
                $db_password = $request->get('DB_PASSWORD');
                $db_port = $request->get('DB_PORT');

                $conn = new \mysqli($db_host, $db_username, $db_password, $db_database, $db_port);

                if ($conn->connect_error) {
                    $message = __('Your database details was incorrect! Recheck and try again.');

                    $validator->getMessageBag()->add('db_database', $message);

                    return redirect()->back()->withInput($request->all())->withErrors($validator);
                }
            }catch(\Exception $e){
                $message = __('Your database details was incorrect! Recheck and try again.');

                $validator->getMessageBag()->add('db_database', $message);

                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }
        }

        $this->environment->save($request->all());

        return redirect()->route('Installer::overview.finish');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        return view('installer::overview.finished');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start()
    {
        $validator = Validator::make([], []);

        try{
            if($code = session()->get($this->installer->prefix . 'code')){
                $this->installer->setVerificationCode($code);
            }else{
                throw new \Exception(__('Invalid session! Please try again.'));
            }

            $this->migrateAndSeed();

            Installer::createLog();
        }catch (\Exception $e) {
            $validator->getMessageBag()->add('verification', $e->getMessage());

            return redirect()->route('Installer::overview.index')
                ->withErrors($validator);
        }

        return redirect()->to('/');
    }
}
