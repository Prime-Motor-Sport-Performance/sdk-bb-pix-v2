<?php

namespace PixApiBB\API;

class API
{
  public $apiUrl = "https://api.hm.bb.com.br/pix/v2/"; // Develop
  public $authUrl = "https://oauth.hm.bb.com.br/oauth/token"; // Develop
  
  public $clientId;
  public $clientSecret;
  public $devAppKey; // "gw-dev-app-key"

  public $debugMode;

  public static function make($clientId, $clientSecret, $devAppKey, $apiUrl = null, $authUrl = null, $debugMode = false)
  { 
    return new self($clientId, $clientSecret, $devAppKey, $apiUrl, $authUrl, $debugMode);
  }
  
  public function __construct($clientId, $clientSecret, $devAppKey, $apiUrl = null, $authUrl = null, $debugMode = false)
  {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->devAppKey = $devAppKey;
    $this->apiUrl = $apiUrl ?? $this->apiUrl;
    $this->authUrl = $authUrl ?? $this->authUrl;
    $this->debugMode = $debugMode;
  }
}