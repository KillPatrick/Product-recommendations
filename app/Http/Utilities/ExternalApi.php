<?php

namespace App\Http\Utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class ExternalApi
{
    /**
     * @param $url
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function get($url)
    {
        $client = new Client();

        try {
            $guzzleResponse = $client->request('GET', $url);
            $response = json_decode($guzzleResponse->getBody()->getContents(), true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
            }
        } catch (ClientException $exception) {
            $response = json_decode($exception->getResponse()->getBody(), true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                $response['error'] = [
                    'code' => $exception->getCode(),
                    'message' => (string)$exception->getResponse()->getBody()
                ];
            }
        } catch (RequestException $exception) {
            throw $exception;
        }

        return $response;
    }
}
