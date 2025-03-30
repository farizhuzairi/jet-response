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
    protected array $error = [];

    public function __construct(
        string $message = '',
        array|int $code = 0,
        array $error = [],
        protected bool $isRendered = false
    )
    {
        if(is_array($code)) {
            $error = $code;
            $code = 0;
        }

        $this->error = $error;

        $msg = $this->use_log_message();
        $message = !empty($message) ? $message : $msg;
        parent::__construct($message, $code);
    }

    public function report(): void
    {
        $level = $this->use_log_level();

        Log::{$level}($this->message, array_merge(
            $this->error,
            [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
            ]
        ));
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
                statusCode: $httpStatus?->code() ?? 500,
                message: $httpStatus?->message(),
            )
            ->send();

        }

        return false;
    }

    protected function use_log_level(): string
    {
        $s = 'logLevelDefault';
        if(property_exists($this, $s)) {
            return $this->{$s};
        }

        return 'error';
    }

    protected function use_log_message(): string
    {
        $s = 'logMessageDefault';
        if(property_exists($this, $s)) {
            return $this->{$s};
        }
        

        return 'Error system.';
    }
}
