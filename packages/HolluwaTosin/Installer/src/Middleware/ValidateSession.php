<?php

namespace HolluwaTosin\Installer\Middleware;

use Closure;
use HolluwaTosin\Installer\Installer;
use HolluwaTosin\Installer\PurchaseDetails;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Cache\Repository as Cache;

class ValidateSession
{
    /**
     * @var Installer
     */
    protected $installer;

    /**
     * CanVerify constructor.
     */
    public function __construct()
    {
        $this->installer = resolve('installer');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $this->installer->prefix . 'code';

        $validator = Validator::make([],[]);

        if($code = session()->get($key)){
            try{
                $details = $this->installer->details($code);

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

                    return redirect()->route('Installer::overview.index')
                        ->withErrors($validator);
                }else{
                    session()->put($key, $code);
                }
            }catch(\Exception $e){
                $validator->getMessageBag()->add(
                    'verification', $e->getMessage()
                );

                return redirect()->route('Installer::overview.index')
                    ->withErrors($validator);
            }
        }else{
            $validator->getMessageBag()->add(
                'verification', 'Please enter your verification code.'
            );

            return redirect()->route('Installer::overview.index')
                ->withErrors($validator);
        }


        return $next($request);
    }
}
