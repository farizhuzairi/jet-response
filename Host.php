<?php

namespace Jet\Response;

use Jet\Response\ResponseService;

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
     * @return static
     */
    public static function make(
        array|string|null $data,
        int $statusCode,
        string $message
    ): static
    {
        return new static($data, $statusCode, $message);
    }
}