<?php

namespace PixApiBB\Helpers;

use GuzzleHttp\Psr7\Response as Psr7Response;

class Response
{
    public $body;

    public static function make(Psr7Response $guzzleResponse)
    {

        return new self($guzzleResponse);
    }

    public function __construct(Psr7Response $guzzleResponse)
    {
        $this->body = json_decode($guzzleResponse->getBody());
    }

    public function success() 
    {
        if (isset($this->body->status)) {

            return $this->body->status >= 200 && $this->body->status < 300;

        } else {
            return true;
        }
    }

}