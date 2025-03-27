<?php

namespace Jet\Response;

use Illuminate\Http\JsonResponse;
use Jet\Response\Http\CallHttpStatus;
use Jet\Response\Http\Resources\JetResource;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class ResponseService
{
    use CallHttpStatus;

    protected JetResource $resource;
    protected array $headers = [];
    protected array $meta = [];
    protected array $author = [];
    protected bool $successful = false;
    
    public function __construct(
        protected array|string|null $data = [],
        protected int $statusCode = 0,
        protected string $message = ""
    )
    {
        $this->setStatusResponse($statusCode);
    }

    protected function makeResource(): void
    {
        $this->resource = (new JetResource(
            data: $this->data,
            successful: $this->successful,
            statusCode: $this->statusCode,
            message: $this->message,
            meta: $this->meta
        ));
    }

    protected function setStatusResponse(int $statusCode = 0): void
    {
        $this->successful = $statusCode >= 200 && $statusCode <= 299 ? true : false;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = (int) $statusCode;
        $this->setStatusResponse($statusCode);
        return $this;
    }

    protected function makeHeaders(JsonResponse|BaseResponse $result): JsonResponse|BaseResponse
    {
        if(!empty($this->headers)){
            foreach($this->headers as $key => $value){
                $result = $result->header($key, $value);
            }
        }
        return $result;
    }

    public function setMessage(string $message): static
    {
        $this->message = (string) $message;
        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = (array) $headers;
        return $this;
    }

    public function addHeader(array|string $headers, array|string|null $value = null): static
    {
        if(is_array($headers)) {
            $this->headers = array_merge($this->headers, $headers);
        }

        elseif(is_string($headers) && ! empty($value)) {
            $this->headers[$headers] = $value;
        }

        return $this;
    }

    public function setMeta(array $meta): static
    {
        $this->meta = (array) $meta;
        return $this;
    }

    public function addMeta(array|string $meta, array|string|null $value): static
    {
        if(is_array($meta)) {
            $this->meta = array_merge($this->meta, $meta);
        }

        elseif(is_string($meta) && !empty($value)) {
            $this->meta[$meta] = $value;
        }

        return $this;
    }

    public function addData(array|string $data, array|string|null $value = null): static
    {
        if(is_array($data)) {
            $this->data = array_merge($this->data, $data);
        }

        elseif(is_string($data) && !empty($value)) {
            $this->data[$data] = $value;
        }

        return $this;
    }

    public function author(array $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function json(): BaseResponse
    {
        $this->setStatusResponse($this->statusCode);
        
        $result = response()
        ->json([
            'data' => [
                'successful' => $this->successful,
                'statusCode' => $this->statusCode,
                'message' => $this->message,
                'results' => $this->data,
            ],
            'meta' => $this->meta,
            'author' => $this->author
        ])
        ->setStatusCode($this->statusCode);

        return $this->makeHeaders($result);
    }

    public function send(): JsonResponse
    {
        $this->makeResource();

        $result = $this->resource
        ->additional(['author' => $this->author]);

        $result = $result
            ->response()
            ->setStatusCode($this->statusCode);

        return $this->makeHeaders($result);
    }
}