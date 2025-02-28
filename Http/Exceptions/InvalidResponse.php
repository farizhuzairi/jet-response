<?php

namespace Jet\Response\Http\Exceptions;

use Exception;
use Jet\Response\Jet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InvalidResponse extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        protected array $error = [],
        protected bool $isRendered = false
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

    public function render(Request $request): Response|bool
    {
        if($this->isRendered) Jet::error()->json();
        return false;
    }
}
