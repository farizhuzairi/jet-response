<?php

namespace JetResponse\Http\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidResponse extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        protected array $error = []
    )
    {
        $msg = "Invalid Jet Response.";
        $message = !empty($message) ? "{$msg} {$message}" : $msg;
        parent::__construct($message, $code);
    }

    public function report(): void
    {
        Log::info($this->message, array_merge($this->error, ['code' => $this->code]));
    }
}
