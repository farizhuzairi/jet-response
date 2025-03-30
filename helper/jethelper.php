<?php

if(! function_exists('defineErrorResponse')){

    function defineErrorResponse(
        string $message,
        int $code = 0,
        bool $isRendered = false,
        array $error = []
    ): array {

        return [
            "message" => $message,
            "code" => $code,
            "error" => $error,
            "isRendered" => $isRendered,
        ];

    }

}