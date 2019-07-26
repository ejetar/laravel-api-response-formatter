<?php
namespace Ejetar\ApiResponseFormatter\App\Providers;

use Ejetar\ApiResponseFormatter\App\Http\Middleware\ResponseFormatter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    public function boot() {
        if (!defined('API_RESPONSE_FORMATTER_ASSERT_ARRAY'))
            define('API_RESPONSE_FORMATTER_ASSERT_ARRAY', [
                'name' => 'Guilherme',
                'surname' => 'Girardi'
            ]);

        $this->loadRoutesFrom(__DIR__.'/../../routes/test.php');

        app('router')->aliasMiddleware('response_formatter', ResponseFormatter::class);
    }

    public function register() {
        //
    }
}
