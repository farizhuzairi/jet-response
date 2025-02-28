<?php

if(! function_exists('defineErrorResponse')){

    function defineErrorResponse(
        string $message,
        string $class,
        string $line,
        int $code = 0,
        bool $isRendered = false,
    ): array {

        return [
            "message" => $message,
            "code" => $code,
            "error" => [
                "class" => $class,
                "line" => $line,
            ],
            "isRendered" => $isRendered,
        ];

    }

}