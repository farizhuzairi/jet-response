<?php

namespace Jet\Response\Http\Contracts;

use Illuminate\Http\JsonResponse;
use Jet\Response\Http\HttpStatus;
use Jet\Response\Http\Resources\JetResource;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class ResponseService
{
    protected JetResource $resourceCollection;
    protected array $headers = [];
    protected array $meta = [];
    protected array $author = [];
    protected bool $successful = false;
    
    public function __construct(
        protected array|string|null $data = [],
        protected int $statusCode = 0,
        protected ?string $message = ""
    )
    {}

    protected function makeResource(): void
    {
        $this->setStatusResponse($this->statusCode);
        $this->setMessageResponse($this->statusCode, $this->message);

        $this->resourceCollection = (new JetResource(
            data: $this->data,
            successful: $this->successful,
            statusCode: $this->statusCode,
            message: $this->message,
            meta: $this->meta
        ));

        $this->resourceCollection
        ->additional(['author' => $this->author]);
    }

    protected function setStatusResponse(int $statusCode): void
    {
        $this->successful = $statusCode >= 200 && $statusCode <= 299 ? true : false;
    }

    protected function setMessageResponse(int $statusCode, string $message): void
    {
        $result = HttpStatus::getObjectByKeyword($statusCode);
        $this->message = "{$result->message()} {$message}";
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = (int) $statusCode;
        $this->setStatusResponse($statusCode);
        return $this;
    }

    protected function responseWithHeader(JsonResponse|BaseResponse $result): JsonResponse|BaseResponse
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

    public function addMeta(array|string $meta, array|string|null $value = null): static
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
        $this->makeResource();
        
        $result = response()
        ->json($this->resourceCollection)
        ->setStatusCode($this->statusCode);

        return $this->responseWithHeader($result);
    }

    public function send(): JsonResponse
    {
        $this->makeResource();

        $result = $this->resourceCollection
            ->response()
            ->setStatusCode($this->statusCode);

        return $this->responseWithHeader($result);
    }

    /**
     * Set http status with static method
     * 
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $message = isset($arguments['message']) ? $arguments['message'] : ( isset($arguments[0]) ? $arguments[0] : null );
        $withDefault = isset($arguments['withDefault']) ? $arguments['withDefault'] : ( isset($arguments[1]) ? $arguments[1] : false );

        $msg = function(?string $defaultMessage, ?string $message, bool $withDefault = false){
            $i = $defaultMessage;
            if(! empty($message)) $i = $withDefault ? "{$i} {$message}" : "{$message}";
            return $i;
        };

        $httpStatus = HttpStatus::getObjectByKeyword($method);
        if(! $httpStatus){
            \Illuminate\Support\Facades\Log::warning("{$method} does not match any http status codes");
            $httpStatus = HttpStatus::ERROR;
        }
        
        return new static(
            data: $arguments['data'] ?? [],
            statusCode: $httpStatus->code(),
            message: $msg($httpStatus->message(), $message, $withDefault)
        );
    }
}