<?php

namespace Jet\Response;

use Illuminate\Http\JsonResponse;
use Jet\Response\Http\Contracts\ResponseService;

class Host extends ResponseService
{
    public function __construct(
        array|string|null $data = [],
        int $statusCode = 0,
        string $message = ""
    )
    {
        parent::__construct(data: $data, statusCode: $statusCode, message: $message);
    }

    /**
     * Objek respon default berdasarkan parameter
     * menggunakan static method
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function make(
        array|string|null $data,
        int $statusCode,
        ?string $message
    ): JsonResponse
    {
        $host = new static($data, $statusCode, $message);
        return $host->send();
    }
}