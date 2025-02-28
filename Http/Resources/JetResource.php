<?php

namespace Jet\Response\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JetResource extends ResourceCollection
{
    public function __construct(
        protected array|string|null $data = [],
        protected bool $successful = false,
        protected int $statusCode = 0,
        protected string $message = "",
        protected array $meta = [],
    )
    {}
    
    public function toArray(Request $request): array
    {
        return [
            'successful' => $this->successful,
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'results' => $this->data
        ];
    }

    public function with(Request $request): array
    {
        return $this->meta;
    }
}
