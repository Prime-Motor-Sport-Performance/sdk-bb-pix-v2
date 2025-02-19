<?php

namespace PixApiBB\Services\Auth;

class ClientCredentials implements IClientCredentials
{
  private $tokenUrl = "https://oauth.hm.bb.com.br/oauth/token";
  private $apiUrl = "https://api.hm.bb.com.br/pix/v2"; // Develop

  private $accessToken;
  private $clientId;
  private $clientSecret;
  private $devAppKey; // "gw-dev-app-key"
  private $grandType = "client_credentials";
  private $contentType = "application/x-www-form-urlencoded";
  private $scopes = [];

  public static function make($clientId, $clientSecret, $devAppKey): IClientCredentials
  {
    return new self($clientId, $clientSecret, $devAppKey);
  }

  public function __construct($clientId, $clientSecret, $devAppKey)
  {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->devAppKey = $devAppKey;
  }

  public function auth(): bool
  {
    return true;
  }

}