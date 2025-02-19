<?php

namespace PixApiBB\Services;

use Exception;
use GuzzleHttp\Client;
use PixApiBB\Helpers\Response;
use PixApiBB\API\API;

class Service
{
  protected array $exceptions;
  protected Client $guzzleClient;
  protected API $api;

  public function __construct($api)
  {

    $this->api = $api;

    $this->guzzleClient = new Client([
      'http_errors' => false,
      'debug' => true,
      'base_uri' => $api->apiUrl,
      'timeout' => 10,
      'curl' => [
        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Force TLS 1.2
      ],
    ]);

  }

  protected function throwException(Response $response)
  {
    $exceptionMessage = "Title: " . $response->body->title . " Detail: " . $response->body->detail;

    if (array_key_exists($response->body->status, $this->exceptions)) {
      throw new ($this->exceptions[$response->body->status])($exceptionMessage);
    } else {
      throw new Exception('[Unknown Exception] ' . $exceptionMessage);
    }
  }
}
