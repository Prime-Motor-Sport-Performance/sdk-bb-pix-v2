<?php

namespace PixApiBB\Helpers;

class Response
{
    public $type; 
    public $title;
    public $status;
    public $detail;

    public static function make($response)
    {
        return new self(
            $response->type,
            $response->title,
            $response->status,
            $response->detail,
        );
    }

    public function __construct($type, $title, $status, $detail)
    {
        $this->type = $type;
        $this->title = $title;
        $this->status = $status;
        $this->detail = $detail;
    }

    public function success() 
    {
        return $this->status >= 200 || $this->status < 300;
    }

}