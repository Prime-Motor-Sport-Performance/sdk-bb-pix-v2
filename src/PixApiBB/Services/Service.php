<?php

namespace PixApiBB\Services;

use PixApiBB\Helpers\Response;

class Service
{
  protected array $exceptions;

  protected function throwException($exceptions, Response $response) 
  {
    throw new ($exceptions[$response->status])("Title: " . $response->title . " Detail: " . $response->detail);
  }
}

