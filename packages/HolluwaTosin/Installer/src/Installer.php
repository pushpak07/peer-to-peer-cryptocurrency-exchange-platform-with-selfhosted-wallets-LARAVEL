<?php

namespace HolluwaTosin\Installer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use HolluwaTosin\Installer\Middleware\CanInstall;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\URL;
use Psr\Http\Message\ResponseInterface;

class Installer
{
    /**
     * Guzzle client instance
     *
     * @var Client
     */
    protected $client;

    /**
     * Validation server's API End point
     *
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $endpoint;

    /**
     * The key used to store verification code in
     * the cache system
     *
     * @var string
     */
    public $prefix = 'verification.';

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Installer constructor.
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->client = new Client([
            'headers' => [
                'Accept' => 'application/x.oluwatosin.v2+json'
            ]
        ]);

        $this->cache = $cache;
    }

    /**
     * @return array|PurchaseDetails
     */
    public function purchaseDetails()
    {
        return $this->details($this->getVerificationCode());
    }

    /**
     * Create installation log
     */
    public static function createLog()
    {
        $file = storage_path('installed');

        if (!file_exists($file)) {
            $contents[] = [
                'status' => 'Installed',
                'date' => date("Y/m/d h:i:s")
            ];
        } else {
            $contents = json_decode(
                file_get_contents($file), true
            );

            $contents[] = [
                'status' => 'Updated',
                'date' => date("Y/m/d h:i:s")
            ];
        }

        file_put_contents(
            $file, json_encode($contents)
        );
    }

    /**
     * Check the details of a licenses
     *
     * @param $code
     * @return array|PurchaseDetails
     */
    public function details($code)
    {
        $endpoint = config('installer.endpoint');

        if (!$this->cache->has($this->prefix . 'details')) {
            try {
                $response = $this->client->get($endpoint, $this->params($code));

                $statusCode = $response->getStatusCode();

                if ($statusCode != 200) {
                    $details = (string) $response->getBody();

                    $this->cache->put($this->prefix . 'details', $details, now()->addDay());

                    return new PurchaseDetails($details);
                } else {
                    return $this->errorMessage($response);
                }
            } catch (ClientException $e) {
                return $this->errorMessage($e->getResponse());
            }
        } else {
            $details = $this->cache->get($this->prefix . 'details');

            return new PurchaseDetails($details);
        }
    }

    /**
     * @param $code
     * @return array|PurchaseDetails
     */
    public function check($code)
    {
        try {
            $endpoint = config('installer.endpoint');

            $response = $this->client->get($endpoint, $this->params($code));

            return new PurchaseDetails((string) $response->getBody());

        } catch (ClientException $e) {
            return $this->errorMessage($e->getResponse());
        }
    }

    /**
     * Clear purchase details
     */
    public function clearDetails()
    {
        $this->cache->forget($this->prefix . 'details');
    }

    /**
     * Prepare the options for api requests
     *
     * @param $code
     * @return array
     */
    public function params($code)
    {
        return [
            'query' => [
                'url'  => URL::to('/'),
                'code' => $code,
            ]
        ];
    }

    /**
     * Prepare error message
     *
     * @param ResponseInterface $response
     * @return array
     */
    public function errorMessage($response)
    {
        $status_code = $response->getStatusCode();

        if ($status_code == 400) {
                    $details = (string) $response->getBody();

                    $this->cache->put($this->prefix . 'details', $details, now()->addDay());

                    return new PurchaseDetails($details);
        } elseif ($status_code == 401) {
                    $details = (string) $response->getBody();

                    $this->cache->put($this->prefix . 'details', $details, now()->addDay());

                    return new PurchaseDetails($details);
        } elseif ($status_code == 404) {
                    $details = (string) $response->getBody();

                    $this->cache->put($this->prefix . 'details', $details, now()->addDay());

                    return new PurchaseDetails($details);
        } elseif ($status_code == 403) {
                    $details = (string) $response->getBody();

                    $this->cache->put($this->prefix . 'details', $details, now()->addDay());

                    return new PurchaseDetails($details);
        }

        return ['error' => $status_code, 'message' => __('Opps! Something went wrong!')];
    }

    /**
     * Get verification code
     *
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->cache->get($this->prefix . 'code');
    }

    /**
     * @param $code
     */
    public function setVerificationCode($code)
    {
        $this->cache->forever($this->prefix . 'code', $code);
    }

    /**
     * Check installation status
     *
     * @return bool
     */
    public function installed()
    {
        return CanInstall::installed();
    }
}
