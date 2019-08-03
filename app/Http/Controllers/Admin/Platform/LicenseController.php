<?php

namespace App\Http\Controllers\Admin\Platform;

use HolluwaTosin\Installer\Installer;
use HolluwaTosin\Installer\PurchaseDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.platform.license.index');
    }

    /**
     * Update license details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|min:10',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        try{
            $details = $this->installer->check($request->code);

            if(!($details instanceof PurchaseDetails)){
                if(is_array($details) && isset($details['error'])){
                    $validator->getMessageBag()->add(
                        'code', $details['message']
                    );
                }else{
                    $validator->getMessageBag()->add(
                        'code', 'Something unexpected went wrong!'
                    );
                }

                return redirect()->back()->withErrors($validator);
            }else{
                $this->installer->setVerificationCode($request->code);
                $this->installer->clearDetails();
            }
        }catch(\Exception $e){
            $validator->getMessageBag()->add('code', $e->getMessage());

            return redirect()->back()->withErrors($validator);
        }

        $message = __('License has been updated!');

        return success_response($message);
    }
}
