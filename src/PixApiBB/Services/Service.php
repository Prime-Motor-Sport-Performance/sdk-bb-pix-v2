<?php

namespace PixApiBB\Services;

use Exception;
use GuzzleHttp\Client;
use PixApiBB\Helpers\Response;
use PixApiBB\API\API;
use PixApiBB\Services\Auth\ClientCredentials\ClientCredentials;
use PixApiBB\Services\Auth\ClientCredentials\IClientCredentials;

class Service
{
  protected array $exceptions;
  protected Client $guzzleClient;
  protected API $api;
  protected IClientCredentials $clientCredentials;

  public function __construct($api)
  {

    $this->clientCredentials = ClientCredentials::make($api);

    $this->api = $api;

    $this->guzzleClient = new Client([
      'http_errors' => false,
      'debug' => $api->debugMode,
      'base_uri' => $api->apiUrl,
      'timeout' => 10,
      'curl' => [
        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Force TLS 1.2
      ],
    ]);

  }

  protected function throwException(Response $response)
  {
   
    $exceptionMessage = json_encode($response->body);

    if (array_key_exists($response->guzzleResponse->getStatusCode(), $this->exceptions)) {
      throw new ($this->exceptions[$response->guzzleResponse->getStatusCode()])($exceptionMessage);
    } else {
      throw new Exception('[Unknown Exception] ' . $exceptionMessage);
    }

  }
}
