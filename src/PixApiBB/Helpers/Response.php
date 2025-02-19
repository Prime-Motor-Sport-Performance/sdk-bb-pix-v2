<?php

namespace PixApiBB\Helpers;

use GuzzleHttp\Psr7\Response as Psr7Response;

class Response
{
    public Psr7Response $guzzleResponse;
    public $body;

    public static function make(Psr7Response $guzzleResponse)
    {
        return new self($guzzleResponse);
    }

    public function __construct(Psr7Response $guzzleResponse)
    {   
        $this->guzzleResponse = $guzzleResponse;

        $this->body = json_decode($guzzleResponse->getBody());
    }

    public function success() 
    {
        $responseCode = $this->guzzleResponse->getStatusCode();
        return $responseCode >= 200 && $responseCode < 300;
    }

}