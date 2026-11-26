<?php

namespace App\Exceptions;

use RuntimeException;

class ForumException extends RuntimeException
{
    public function __construct(
        string $message,
        protected int $statusCode = 422
    ) {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
