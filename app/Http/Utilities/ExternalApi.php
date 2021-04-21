<?php

namespace App\Http\Utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ExternalApi
{
    private $guzzle;
    private $url;

    public function __construct(Client $guzzle, $url) {
        $this->guzzle = new $guzzle();
        $this->url = $url;
    }
    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        $client = $this->guzzle;

        try {
            $guzzleResponse = $client->request('GET', $this->url);
            $response = (string) $guzzleResponse->getBody();
        } catch (ClientException $exception) {
            $response = (string) $exception->getResponse()->getBody();
        } catch (RequestException $exception) {
            throw $exception;
        }

        return $response;
    }
}
