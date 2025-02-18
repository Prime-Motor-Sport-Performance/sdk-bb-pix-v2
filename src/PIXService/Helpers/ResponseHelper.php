<?php

namespace PIXService\Helpers;

class ResponseHelper
{
    public static function success($message, $data = null, $code = null)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }
        
        if ($code) {
            $response['code'] = $code;
        }

        return $response;
    }

    public static function error($message, $data = null, $debugMessage = null, $code = null)
    {
    
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        if ($debugMessage) {
            $response['debugMessage'] = $debugMessage;
        }

        if ($code) {
            $response['code'] = $code;
        }

        return $response;
    }
}