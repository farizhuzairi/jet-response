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
            'message' => trim($this->message),
            'results' => $this->data
        ];
    }

    public function with(Request $request): array
    {
        if(array_key_exists('meta', $this->with)) {
            return array_merge($this->with, $this->meta);
        }

        return array_merge($this->with, [
            'meta' => $this->meta
        ]);
    }
}
