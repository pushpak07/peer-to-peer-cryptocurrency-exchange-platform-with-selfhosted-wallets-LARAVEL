<?php

namespace App\Http\Controllers\Admin\Platform;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IntegrationController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.platform.integration.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'root_url' => [
                'nullable', 'url', function ($attribute, $value, $fail) {
                    $curr_url = parse_url(request()->fullUrl());
                    $root_url = parse_url($value);

                    if ($curr_url["host"] == $root_url['host']) {
                        $fail(__('You can not use the same domain!'));
                    }
                }
            ]
        ]);

        platformSettings()->update([
            'root_url'          => $request->root_url,
            'allowed_public_ip' => $request->allowed_public_ip,
        ]);

        $message = __('Settings has been updated!');

        return success_response($message);
    }
}
