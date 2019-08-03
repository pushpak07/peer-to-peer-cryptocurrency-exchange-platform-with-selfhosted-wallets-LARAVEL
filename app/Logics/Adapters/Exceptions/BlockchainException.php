<?php
/**
 * ======================================================================================================
 * File Name: BlockchainException.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 11/8/2018 (6:14 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Adapters\Exceptions;

use Exception;
use Illuminate\Http\Request;

class BlockchainException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
        if ($request->ajax()) {
            $message = ucfirst($this->getMessage());

            if($request->expectsJson()){
                return response()->json($message, 422);
            }

            return response($message, 422);
        }
    }
}
