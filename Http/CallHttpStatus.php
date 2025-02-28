<?php

namespace Jet\Response\Http;

use Jet\Response\Http\HttpStatus;
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
        $message = "";

        $msg = function(string $defaultMessage, string $message){
            $i = $defaultMessage;
            if(!empty($message)) $i = "{$i} {$message}";
            return $i;
        };

        $httpStatus = HttpStatus::getObjectByKeyword($method);
        if($httpStatus) {
            $statusCode = $httpStatus->code();
            $message = $arguments['message'] ?? $httpStatus->message();
        }

        if($statusCode > 0 || ! $httpStatus){
            $data = $arguments['data'] ?? [];
            $error = HttpStatus::ERROR;
            return new static(
                data: $data,
                statusCode: $error->code(),
                message: $msg($error->message(), $message)
            );
        }
        
        report(new InvalidResponse(...defineErrorResponse("Invalid Http status method.", __CLASS__, __LINE__, 500)));
        return null;
    }
}