<?php
/**
 * ======================================================================================================
 * File Name: Adapter.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 10/17/2018 (11:58 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Adapters\Traits;


use App\Logics\Adapters\Exceptions\BlockchainException;
use App\Models\BitcoinWallet;
use App\Models\DashWallet;
use App\Models\LitecoinWallet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait Adapter
{
    /**
     * @param BitcoinWallet|LitecoinWallet|DashWallet $wallet
     * @return mixed
     */
    public function getWallet($wallet)
    {
        $this->express->walletId = $wallet->wallet_id;

        return $this->express->getWallet();
    }

    /**
     * @param $label
     * @param $passphrase
     * @return mixed
     * @throws \Exception
     */
    public function generateWallet($label, $passphrase)
    {
        $approvals = (int) config()->get('settings.min_tx_confirmations');

        $wallet = $this->express->generateWallet(
            $label, $passphrase
        );

        if (!$wallet) {
            throw new BlockchainException(__('Unable to connect to blockchain network!'));
        }

        if (isset($wallet['error'])){
            throw new BlockchainException($wallet['error']);
        }

        $this->express->walletId = $wallet['id'];

        $hook = $this->express->addWalletWebhook($this->getWebhookUrl(), 'transfer');

        if (!$hook) {
            throw new BlockchainException(__('Unable to connect to blockchain network!'));
        }

        if (isset($hook['error'])){
            throw new BlockchainException($hook['error']);
        }

        $hook = $this->express->addWalletWebhook(
            $this->getWebhookUrl(), 'transfer', $approvals
        );

        if (!$hook) {
            throw new BlockchainException(__('Unable to connect to blockchain network!'));
        }

        if (isset($hook['error'])){
            throw new BlockchainException($hook['error']);
        }

        return $wallet;
    }

    /**
     * @param BitcoinWallet|LitecoinWallet|DashWallet $wallet
     * @throws \Exception
     * @return mixed
     */
    public function createWalletAddress($wallet)
    {
        $this->express->walletId = $wallet->wallet_id;

        $address = $this->express->createWalletAddress();

        if (!$address) {
            throw new BlockchainException(__('Unable to connect to blockchain network!'));
        }

        if (isset($address['error'])){
            throw new BlockchainException($address['error']);
        }

        return $address;
    }

    /**
     * @param BitcoinWallet|LitecoinWallet|DashWallet $wallet
     * @param string $output
     * @param int $amount
     *
     * @return mixed
     * @throws \Exception
     */
    public function send($wallet, $output, $amount)
    {
        $this->express->walletId = $wallet->wallet_id;

        $num_blocks = (int) config()->get('settings.tx_num_blocks');

        if ($amount < 0) {
            $result = $this->express->sweep(
                $output, $wallet->passphrase
            );

            if (!$result) {
                throw new BlockchainException(__('Unable to connect to blockchain network!'));
            }

            if (isset($result['error'])){
                throw new BlockchainException($result['error']);
            }

            $wallet->update(['balance' => 0]);

            return $result;
        } else {
            $result = $this->express->sendTransaction(
                $output, $amount, $wallet->passphrase, null, $num_blocks
            );

            if (!$result) {
                throw new BlockchainException(__('Unable to connect to blockchain network!'));
            }

            if (isset($result['error'])){
                throw new BlockchainException($result['error']);
            }

            $this->updateOutputBalance($output, $amount);

            $this->updateInputBalance($wallet, $result['transfer']);

            $this->storeTransaction($wallet, $result['transfer']);

            return $result['transfer'];
        }
    }

    /**
     * @param BitcoinWallet|LitecoinWallet|DashWallet $wallet
     * @param $outputs
     * @return mixed
     * @throws \Exception
     */
    public function sendMultiple($wallet, $outputs)
    {
        $this->express->walletId = $wallet->wallet_id;

        $num_blocks = (int) config()->get('settings.tx_num_blocks');

        $result = $this->express->sendTransactionToMany(
            $outputs, $wallet->passphrase, null, $num_blocks
        );

        if (!$result) {
            throw new BlockchainException(__('Unable to connect to blockchain network!'));
        }

        if (isset($result['error'])){
            throw new BlockchainException($result['error']);
        }

        $transfer = $result['transfer'];

        $this->updateOutputBalance($outputs);

        $this->updateInputBalance($wallet, $transfer);

        $this->storeTransaction($wallet, $transfer);

        return $transfer;
    }

    /**
     * @param BitcoinWallet|LitecoinWallet|DashWallet $wallet
     * @param $data
     * @return mixed
     */
    protected function storeTransaction($wallet, $data)
    {
        return $wallet->transactions()->create([
            'type' => $data['type'],
            'hash' => $data['txid'],
            'confirmations' => $data['confirmations'] ?? 0,
            'transaction_id' => $data['id'],
            'state' => $data['state'],
            'date' => Carbon::parse($data['date']),
            'value' => $data['value'],
        ]);
    }
}
