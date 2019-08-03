<?php

namespace App\Jobs\Transactions;

use App\Logics\Adapters\LitecoinAdapter;
use App\Models\LitecoinAddress;
use App\Models\LitecoinTransaction;
use App\Models\LitecoinWallet;
use App\Notifications\Transactions\IncomingConfirmed;
use App\Notifications\Transactions\IncomingUnconfirmed;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessLitecoin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Transaction Data
     *
     * @var array
     */
    public $data;

    /**
     * @var
     */
    protected $settings;

    /**
     * @var LitecoinAdapter
     */
    protected $helper;

    /**
     * Create a new job instance.
     *
     * @param $transaction
     * @return void
     */
    public function __construct($data)
    {
        $this->settings = config('settings');
        $this->data = $data;
    }

    /**
     * @param LitecoinAdapter $adapter
     * @throws \Exception
     */
    public function handle(LitecoinAdapter $adapter)
    {
        $adapter->express->walletId = $this->data['wallet'];

        $wallet = LitecoinWallet::where('wallet_id', $this->data['wallet'])->first();

        if (!$wallet) return;

        $tx = $adapter->express->getWalletTransfer($this->data['hash']);

        if (!$tx) {
            throw new \Exception(__('Unable to connect to blockchain network!'));
        }

        if (isset($tx['error'])){
            throw new \Exception($tx['error']);
        }

        $confirmations = (int) $tx['confirmations'] ?? 0;

        $transaction = $wallet->transactions()
            ->where('hash', $this->data['hash'])
            ->first();

        if($tx['type'] == 'send'){
            if(!$transaction){
                $transaction = $wallet->transactions()->create([
                    'type'           => $tx['type'],
                    'hash'           => $tx['txid'],
                    'confirmations'  => $confirmations,
                    'transaction_id' => $tx['id'],
                    'state'          => $tx['state'],
                    'date'           => Carbon::parse($tx['date']),
                    'value'          => $tx['value'],
                ]);
            }

            if ($confirmations >= 1) {
                $result = $adapter->express->getWallet();

                if (!$result) {
                    throw new \Exception(__('Unable to connect to blockchain network!'));
                }

                if (isset($result['error'])) {
                    throw new \Exception($result['error']);
                }

                $wallet->update([
                    'balance' => $result['confirmedBalance']
                ]);
            }
        }

        if($tx['type'] == 'receive'){
            $min_confirmations = (int) $this->settings['min_tx_confirmations'];

            if ($transaction) {
                if ($confirmations >= $min_confirmations) {
                    if ($user = $wallet->user) {
                        $user->notify(new IncomingConfirmed('ltc', $tx['value']));
                    }

                    $result = $adapter->express->getWallet();

                    if (!$result) {
                        throw new \Exception(__('Unable to connect to blockchain network!'));
                    }

                    if (isset($result['error'])) {
                        throw new \Exception($result['error']);
                    }

                    $wallet->update(['balance' => $result['confirmedBalance']]);
                }
            } else {
                $transaction = $wallet->transactions()->create([
                    'type' => $tx['type'],
                    'hash' => $tx['txid'],
                    'confirmations' => $confirmations,
                    'transaction_id' => $tx['id'],
                    'state' => $tx['state'],
                    'date' => Carbon::parse($tx['date']),
                    'value' => $tx['value'],
                ]);

                if ($confirmations < $min_confirmations) {
                    if ($user = $wallet->user) {
                        $user->notify(new IncomingUnconfirmed('ltc', $tx['value']));
                    }
                }
            }
        }

        if($transaction){
            $transaction->update([
                'confirmations' => $confirmations,
                'state' => $tx['state'],
            ]);
        }

    }

}
