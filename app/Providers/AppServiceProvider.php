<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // add api response macro
        Response::macro('api', function ($message = '', $data = null,  $status = 200) {
            $jsonResponse = [
                'message' => $message
            ];
            if ($data) {
                $jsonResponse['data'] = $data;
            }
            return response()->json($jsonResponse, $status);
        });
    }
}
