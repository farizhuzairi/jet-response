<?php

namespace JetResponse;

use JetResponse\Http\CallHttpStatus;
use Illuminate\Http\JsonResponse;
use JetResponse\Http\Resources\JetResource;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

abstract class Response
{
    use CallHttpStatus;

    /**
     * Data objek yang harus tersedia
     * untuk diinisiasi
     * 
     */
    protected JetResource $resource;
    protected array $headers = [];
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
        $this->resource = new JetResource(
            data: $this->data,
            successful: $this->successful,
            statusCode: $this->statusCode,
            message: $this->message
        );
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
     * Mengembalikan objek Response
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function json(): BaseResponse
    {
        $this->setStatusResponse($this->statusCode);

        $result = response()->json([
            'successful' => $this->successful,
            'statusCode' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data
        ])
        ->setStatusCode($this->statusCode);

        $result = $this->makeHeaders($result);

        // result
        return $result;
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

        $result = $this->resource->response()
        ->setStatusCode($this->statusCode);

        $result = $this->makeHeaders($result);

        // result
        return $result;
    }
}