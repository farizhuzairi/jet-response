<?php

namespace Jet\Response\Http;

use Jet\Response\Http\HttpStatus;
use Illuminate\Support\Facades\Log;
use Jet\Response\Http\Exceptions\InvalidResponse;

trait CallHttpStatus
{
    /**
     * Set http status with static method
     * 
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $statusCode = 0;
        $message = isset($arguments['message']) ? $arguments['message'] : ( isset($arguments[0]) ? $arguments[0] : null );
        $withDefault = isset($arguments['withDefault']) ? $arguments['withDefault'] : ( isset($arguments[1]) ? $arguments[1] : false );

        $msg = function(?string $defaultMessage, ?string $message, bool $withDefault = false){
            $i = $defaultMessage;
            if(! empty($message)) $i = $withDefault ? "{$i} {$message}" : "{$message}";
            return $i;
        };

        $httpStatus = HttpStatus::getObjectByKeyword($method);
        if(! $httpStatus){
            Log::warning("{$method} does not match any http status codes");
            $httpStatus = HttpStatus::ERROR;
        }
        
        return new static(
            data: $arguments['data'] ?? [],
            statusCode: $httpStatus->code(),
            message: $msg($httpStatus->message(), $message, $withDefault)
        );
    }
}