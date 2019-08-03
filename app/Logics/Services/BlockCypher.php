<?php
/**
 * ======================================================================================================
 * File Name: BlockCypher.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 10/1/2018 (10:29 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Services;


use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\Random\Rfc6979;
use BitWasp\Bitcoin\Key\PrivateKeyFactory;
use BitWasp\Buffertools\Buffer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Log;

class BlockCypher
{
    /**
     * BlockCypher token
     *
     * @var array
     */
    protected $config;

    /**
     * @var
     */
    protected $settings;

    /**
     * Blockcypher API context
     *
     * @var string
     */
    protected $client;

    /**
     * BlockCypher constructor.
     *
     * @param string $coin
     * @param string $chain
     */
    public function __construct($coin = 'btc', $chain = 'main')
    {
        $this->config = config('blockcypher');

        $this->settings = config('settings');

        if (in_array($this->config['env'], ['sandbox', 'test'])) {
            $coin = 'bcy';

            $chain = 'test';
        }

        $client = new Client([
            'base_uri' => "https://api.blockcypher.com/v1/{$coin}/{$chain}/",
            'query' => ['token' => $this->config['token']]
        ]);

        $this->client = $client;
    }

    /**
     * @param $wallet
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function generateAddress()
    {
        try {
            $response = $this->client->request(
                'POST', "addrs"
            );

            sleep(1);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                throw new \Exception(
                    $response->getBody()->getContents(),
                    $response->getStatusCode()
                );
            }
        }
    }

    /**
     * @param $address
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getBalance($address)
    {
        try {
            $response = $this->client->request(
                'GET', "addrs/{$address}/balance"
            );

            sleep(1);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                throw new \Exception(
                    $response->getBody()->getContents(),
                    $response->getStatusCode()
                );
            }
        }
    }

    /**
     * @param $inputs
     * @param $input_keys
     * @param $outputs
     * @param $change_address
     * @return mixed
     * @throws
     */
    public function createTransaction($inputs, $input_keys, $outputs, $change_address = null)
    {
        try {
            $preference = $this->settings['tx_preference'];

            $response = $this->client->request(
                'POST', "txs/new", [
                    'json' => [
                        'preference' => $preference,
                        'change_address' => $change_address,
                        'inputs' => $inputs,
                        'outputs' => $outputs,
                    ]
                ]
            );

            sleep(1);

            $tx_skeleton = json_decode($response->getBody(), true);

            $response = $this->client->request(
                'POST', "txs/send", [
                    'json' => $this->signTransaction(
                        $tx_skeleton, $input_keys['public'], $input_keys['private']
                    )
                ]
            );

            sleep(1);

            $tx_skeleton = json_decode($response->getBody(), true);

            return $tx_skeleton['tx'];
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                throw new \Exception(
                    $response->getBody()->getContents(),
                    $response->getStatusCode()
                );
            }
        }
    }

    /**
     * @param $hook_id
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function deleteWebhook($hook_id)
    {
        try {
            $this->client->request(
                'DELETE', "hooks/{$hook_id}"
            );

            sleep(1);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                throw new \Exception(
                    $response->getBody()->getContents(),
                    $response->getStatusCode()
                );
            }
        }
    }


    /**
     * @param $address
     * @param $url
     * @param null $hook_id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createWebhook($address, $url)
    {
        try {
            $confirmations = (int) $this->settings['min_tx_confirmations'];

            $response = $this->client->request(
                'POST', 'hooks', [
                    'json' => [
                        'url' => $url,
                        'confirmations' => $confirmations,
                        'address' => $address,
                        'event' => 'tx-confirmation',
                    ]
                ]
            );

            sleep(1);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                throw new \Exception(
                    $response->getBody()->getContents(),
                    $response->getStatusCode()
                );
            }
        }
    }

    /**
     * @param $tx_skeleton
     * @param $public_keys
     * @param $private_keys
     * @return mixed
     * @throws \Exception
     */
    public function signTransaction($tx_skeleton, $public_keys, $private_keys)
    {
        $tosign = $tx_skeleton['tosign'];
        $inputs = $tx_skeleton['tx']['inputs'];

        $signatures = $pubkeys = array();

        for ($i = 0; $i < count($inputs); $i++) {
            $input = $inputs[$i];

            foreach ($input['addresses'] as $address) {
                $signatures[] = $this->sign(
                    $tosign[$i], $private_keys[$address]
                );

                $pubkeys[] = $public_keys[$address];
            }
        }

        $tx_skeleton['signatures'] = $signatures;
        $tx_skeleton['pubkeys'] = $pubkeys;

        return $tx_skeleton;
    }

    /**
     * @param $data
     * @param $private_key
     * @return string
     * @throws \Exception
     */
    public function sign($data, $private_key)
    {
        // Convert hex data to buffer
        $data = Buffer::hex($data);

        $private_key = PrivateKeyFactory::fromHex($private_key);

        $ecAdapter = Bitcoin::getEcAdapter();

        // Deterministic digital signature generation
        $key = new Rfc6979($ecAdapter, $private_key, $data, 'sha256');

        $signature = $ecAdapter->sign($data, $private_key, $key);

        return $signature->getHex();
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->config['token'];
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getConfig()
    {
        return $this->config;
    }
}
