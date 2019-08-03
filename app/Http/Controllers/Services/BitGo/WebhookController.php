<?php

namespace App\Http\Controllers\Services\BitGo;

use App\Jobs\Transactions\ProcessBitcoin;
use App\Jobs\Transactions\ProcessDash;
use App\Jobs\Transactions\ProcessLitecoin;
use App\Logics\Services\BlockCypher;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * @param Request $request
     */
    public function handleBitcoin(Request $request)
    {
        if ($request->type == 'transfer') {
            ProcessBitcoin::dispatch($request->all());
        }
    }

    /**
     * @param Request $request
     */
    public function handleDash(Request $request)
    {
        if ($request->type == 'transfer') {
            ProcessDash::dispatch($request->all());
        }
    }

    /**
     * @param Request $request
     */
    public function handleLitecoin(Request $request)
    {
        if ($request->type == 'transfer') {
            ProcessLitecoin::dispatch($request->all());
        }
    }
}
