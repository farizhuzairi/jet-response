<?php

namespace JetResponse\Http;

use JetResponse\Http\Exceptions\InvalidResponse;

trait CallHttpStatus
{
    public static function __callStatic(string $method, array $arguments)
    {
        $statusCode = 0;
        $defaultMessage = "";
        $msg = function(string $defaultMessage, string $message){
            $i = $defaultMessage;
            if(!empty($message)) $i = "{$i} {$message}";
            return $i;
        };

        switch($method) {
            case 'ok':
                $statusCode = 200;
                $defaultMessage = "Ok.";
                $message = $arguments['message'] ?? "";
                break;
            case 'created':
                $statusCode = 201;
                $defaultMessage = "Request successful.";
                $message = $arguments['message'] ?? "";
                break;
            case 'accepted':
                $statusCode = 202;
                $defaultMessage = "Request has been accepted.";
                $message = $arguments['message'] ?? "";
                break;
            case 'movedPermanently':
                $statusCode = 301;
                $defaultMessage = "The resource URL is not available.";
                $message = $arguments['message'] ?? "";
                break;
            case 'found':
                $statusCode = 302;
                $defaultMessage = "The resource is not available at this time.";
                $message = $arguments['message'] ?? "";
                break;
            case 'badRequest':
                $statusCode = 400;
                $defaultMessage = "Access not found.";
                $message = $arguments['message'] ?? "";
                break;
            case 'invalidToken':
                $statusCode = 401;
                $defaultMessage = "Token missing or invalid key.";
                $message = $arguments['message'] ?? "";
                break;
            case 'unauthorized':
                $statusCode = 401;
                $defaultMessage = "Requires authentication.";
                $message = $arguments['message'] ?? "";
                break;
            case 'paymentRequired':
                $statusCode = 402;
                $defaultMessage = "Payment required.";
                $message = $arguments['message'] ?? "";
                break;
            case 'forbidden':
                $statusCode = 403;
                $defaultMessage = "Invalid access.";
                $message = $arguments['message'] ?? "";
                break;
            case 'notFound':
                $statusCode = 404;
                $defaultMessage = "Access or data not found.";
                $message = $arguments['message'] ?? "";
                break;
            case 'requestTimeout':
                $statusCode = 408;
                $defaultMessage = "Too many requests failed to process.";
                $message = $arguments['message'] ?? "";
                break;
            case 'conflict':
                $statusCode = 409;
                $defaultMessage = "Request not recognized.";
                $message = $arguments['message'] ?? "";
                break;
            case 'tooManyRequests':
                $statusCode = 429;
                $defaultMessage = "The request exceeds the specified limit.";
                $message = $arguments['message'] ?? "";
                break;
            case 'serverError':
                $statusCode = 500;
                $defaultMessage = "There was a problem with the internal server.";
                $message = $arguments['message'] ?? "";
                break;
            case 'badGateway':
                $statusCode = 502;
                $defaultMessage = "An error occurred on the server.";
                $message = $arguments['message'] ?? "";
                break;
            default:
                $statusCode = 0;
                $defaultMessage = "";
                $message = "";
                break;
        }

        // result
        if($statusCode > 0){
            $data = $arguments['data'] ?? [];
            return new static(
                data: $data,
                statusCode: $statusCode,
                message: $msg($defaultMessage, $message)
            );
        }
        
        throw new InvalidResponse("Permintaan tidak valid #code {$statusCode}, #method {$method}");
        // throw new \Exception("Error Processing Request: Permintaan tidak valid #code {$statusCode}, #method {$method}");
        
        // optional
        return null;
    }
}