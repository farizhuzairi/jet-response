<?php

namespace Jet\Response\Http;

use Jet\Response\Host;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Contracts\Foundation\Application;

class JetResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \Illuminate\Http\Request::macro('baseTheme', function() {
            return new Host();
        });
    }
    
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
