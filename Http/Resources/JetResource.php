<?php

namespace JetResponse\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JetResource extends ResourceCollection
{
    public function __construct(
        protected array|string|null $data = [],
        protected bool $successful = false,
        protected int $statusCode = 0,
        protected string $message = "",
    )
    {}
    
    public function toArray(Request $request): array
    {
        return [
            'successful' => $this->successful,
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
