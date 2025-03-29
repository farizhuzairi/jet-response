<?php

namespace Jet\Response\Http;

use Jet\Response\Host;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Contracts\Foundation\Application;

class JetResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Request::macro('host', function(array $data, int $statusCode) {
            return new Host($data, $statusCode);
        });
    }
    
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
