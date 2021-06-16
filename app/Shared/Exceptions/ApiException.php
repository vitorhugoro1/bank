<?php

namespace App\Shared\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function render($request)
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if (config('app.debug', false)) {
            $response['file'] = $this->getFile();
            $response['line'] = $this->getLine();
            $response['trace'] = $this->getTrace();
        }

        return response()->json($response, $this->getCode(), $this->getHeaders());
    }

    public function getStatusCode()
    {
        return $this->statusCode ?? 500;
    }

    public function getHeaders()
    {
        return $this->headers ?? [];
    }
}
