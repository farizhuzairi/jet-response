<?php

namespace JetResponse\Http\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidResponse extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 502,
        protected bool $isRender = true
    )
    {
        $msg = "Invalid Response.";
        $message = !empty($message) ? "{$msg} {$message}" : $msg;
        parent::__construct($message, $code);
    }

    public function render(Request $request): Response|bool
    {
        if($request->routeIs('api.*')) {
            return Jet::badGateway()->json();
        }
        return false;
    }
}
