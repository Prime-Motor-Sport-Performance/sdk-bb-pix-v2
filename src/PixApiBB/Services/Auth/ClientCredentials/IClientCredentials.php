<?php

namespace PixApiBB\Services\Auth;

interface IClientCredentials 
{
  public static function make($clientId, $clientSecret, $devAppKey): IClientCredentials;
  public function auth(): bool;
}