<?php

namespace PixApiBB\Services\Auth\ClientCredentials;

use PixApiBB\API\API;

interface IClientCredentials 
{
  public static function make(API $api): IClientCredentials;
  public function getAccessToken(): string;
}