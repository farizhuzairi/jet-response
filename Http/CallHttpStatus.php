<?php

namespace JetResponse\Http;

use JetResponse\Http\HttpStatus;
use JetResponse\Http\Exceptions\InvalidResponse;

trait CallHttpStatus
{
    /**
     * Get Http Status Object
     * 
     * @return \JetResponse\Http\HttpStatus|null
     */
    private static function getHttpStatusObject(?string $keyword): ?HttpStatus
    {
        $httpStatus = HttpStatus::data();
        $result = array_keys(array_filter($httpStatus, function ($value) use ($keyword) {
            return stripos($value, "#{$keyword}#") !== false;
        }));

        if(isset($result[0])) {
            $result = constant("\JetResponse\Http\HttpStatus::{$result[0]}");
        }
        else{
            report(new InvalidResponse(...defineErrorResponse("{$keyword} is not available in HttpStatus enum.", __CLASS__, __LINE__, 500)));
            $result = null;
        }

        return $result;
    }

    public static function __callStatic(string $method, array $arguments)
    {
        $statusCode = 0;
        $message = "";

        $msg = function(string $defaultMessage, string $message){
            $i = $defaultMessage;
            if(!empty($message)) $i = "{$i} {$message}";
            return $i;
        };

        $httpStatus = static::getHttpStatusObject($method);
        if($httpStatus) {
            $statusCode = $httpStatus->code();
            $message = $arguments['message'] ?? $httpStatus->message();
        }

        if($statusCode > 0 || empty($httpStatus)){

            $data = $arguments['data'] ?? [];
            $error = HttpStatus::ERROR;
            return new static(
                data: $data,
                statusCode: $error->code(),
                message: $msg($error->message(), $message)
            );
        }
        
        return null;
    }
}