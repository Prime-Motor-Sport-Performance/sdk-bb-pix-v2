<?php

namespace PixApiBB\Services\Auth\ClientCredentials;

use Exception;
use GuzzleHttp\Client;
use PixApiBB\API\API;

class ClientCredentials implements IClientCredentials
{
  private API $api;

  private $grantType = "client_credentials";
  private $contentType = "application/x-www-form-urlencoded";
  private $scopes = [];
  
  public $guzzleClient;
  public $accessToken;

  public static function make(API $api): IClientCredentials
  {
    return new self($api);
  }

  public function __construct(API $api)
  {
    $this->api = $api;
  }

  /**
   * 
   */
  public function getAccessToken(): string
  {

    try {

      $this->guzzleClient = new Client([
        // 'base_uri' => $this->apiUrl,
        'timeout' => 10,
        'curl' => [
          CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Force TLS 1.2
        ],
      ]);

      $response = $this->guzzleClient->post($this->api->authUrl, [
        'debug' => false,
        'headers' => [
          'Authorization' => 'Basic ' . base64_encode($this->api->clientId .':'. $this->api->clientSecret),
          'Content-Type' => $this->contentType,
        ],
        'query' => [
          'grant_type' => $this->grantType,
          'scopes' => $this->scopes,
        ],
      ]);

      $this->accessToken = json_decode($response->getBody())->access_token;

      return $this->accessToken;

    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
