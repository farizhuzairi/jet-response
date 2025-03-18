<?php

namespace Jet\Response;

use Jet\Response\Http\CallHttpStatus;
use Illuminate\Http\JsonResponse;
use Jet\Response\Http\Resources\JetResource;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class ResponseService
{
    use CallHttpStatus;

    /**
     * Data objek yang harus tersedia
     * untuk diinisiasi
     * 
     */
    protected JetResource $resource;
    protected array $headers = [];
    protected array $meta = [];
    protected bool $successful = false;
    
    public function __construct(
        protected array|string|null $data = [],
        protected int $statusCode = 0,
        protected string $message = ""
    )
    {
        $this->setStatusResponse($statusCode);
    }

    /**
     * Membangun objek secara manual
     * 
     * @return void
     */
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

    /**
     * Menyetel status respon berdasarkan kode status
     * 
     * @return void
     */
    protected function setStatusResponse(int $statusCode = 0): void
    {
        $this->successful = $statusCode >= 200 && $statusCode <= 299 ? true : false;
    }

    /**
     * Set response status
     * 
     * @return static
     */
    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = (int) $statusCode;
        $this->setStatusResponse($statusCode);
        return $this;
    }

    /**
     * Menyediakan informasi dan identitas pada http header
     * 
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function makeHeaders(JsonResponse|BaseResponse $result): JsonResponse|BaseResponse
    {
        if(!empty($this->headers)){
            foreach($this->headers as $key => $value){
                $result = $result->header($key, $value);
            }
        }
        return $result;
    }

    /**
     * Set message data response
     * 
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = (string) $message;
        return $this;
    }

    /**
     * Set http header
     * 
     * @return static
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = (array) $headers;
        return $this;
    }

    /**
     * Add http header
     * 
     * @return static
     */
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

    /**
     * Set Metadata
     * 
     * @return static
     */
    public function setMeta(array $meta): static
    {
        $this->meta = (array) $meta;
        return $this;
    }

    /**
     * Add Metadata
     * 
     * @return static
     */
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

    /**
     * add Data
     * 
     * @return static
     */
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

    /**
     * Mengembalikan objek Response
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function json(): BaseResponse
    {
        $this->setStatusResponse($this->statusCode);
        
        $result = response()
        ->json([
            'successful' => $this->successful,
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'results' => $this->data,
            'meta' => $this->meta
        ])
        ->setStatusCode($this->statusCode);

        return $this->makeHeaders($result);
    }

    /**
     * Mengembalikan objek JsonResponse
     * melalui data array
     * .................................
     * string $type = 'json' is Default
     * .................................
     * berasal dari inisiasi GateResponse::class 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): JsonResponse
    {
        $this->makeResource();

        $result = $this->resource;

        // if($this->meta) {
        //     $result = $result
        //     ->additional($this->meta);
        // }

        $result = $result
            ->response()
            ->setStatusCode($this->statusCode);

        return $this->makeHeaders($result);
    }
}