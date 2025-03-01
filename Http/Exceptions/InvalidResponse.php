<?php

namespace Jet\Response\Http\Exceptions;

use Exception;
use Jet\Response\Host;
use Illuminate\Http\Request;
use Jet\Response\Http\HttpStatus;
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
        Log::info($this->message, array_merge($this->error, ['code_fromObject' => $this->code]));
    }

    public function render(Request $request): Response|bool
    {
        if($this->isRendered) {
            
            Log::warning(
                $this->message,
                array_merge(
                    $this->error,
                    [
                        'notice' => 'Response rendered in Exception ' . __CLASS__,
                        'code_fromObject' => $this->code,
                    ]
                )
            );

            $httpStatus = HttpStatus::getObjectByKeyword($this->code);

            if(! $httpStatus) {
                Log::notice("Invalid HttpStatus:::getObjectByKeyword({$this->code}) enum object with code {$this->code}");
            }

            return Host::make(
                data: [],
                statusCode: $httpStatus->code() ?? 500,
                message: $httpStatus->message() ?? "",
            )
            ->send();

        }

        return false;
    }
}
