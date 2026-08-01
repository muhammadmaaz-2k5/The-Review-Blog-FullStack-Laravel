<?php

namespace App\Exceptions;

use Exception;

class MovieBoxException extends Exception
{
    protected $statusCode;
    protected $errorCode;

    public function __construct(string $message, int $statusCode = 400, string $errorCode = 'MOVIEBOX_ERROR', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'error_code' => $this->getErrorCode(),
            'timestamp' => now()->toISOString()
        ], $this->getStatusCode());
    }
}
