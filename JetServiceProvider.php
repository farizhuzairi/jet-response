<?php

namespace JetResponse;

use JetResponse\Jet;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Contracts\Foundation\Application;

class JetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \Illuminate\Http\Request::macro('baseTheme', function() {
            return new Jet();
        });
    }
    
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
