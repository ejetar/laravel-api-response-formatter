<?php

namespace Ejetar\ApiResponseFormatter\Providers;

use Ejetar\ApiResponseFormatter\Http\Middleware\ApiResponseFormatter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	public function boot() {
		if (!defined('API_RESPONSE_FORMATTER_ASSERT_ARRAY'))
			define('API_RESPONSE_FORMATTER_ASSERT_ARRAY', [
				'name'    => 'Guilherme',
				'surname' => 'Girardi'
			]);

		$this->loadRoutesFrom(__DIR__ . '/../../routes/test.php');

		app('router')->aliasMiddleware('api-response-formatter', ApiResponseFormatter::class);

		//        $this->publishes([
		//            __DIR__ . '/../../config/response-formatter.php' => config_path('response-formatter.php'),
		//        ]);
	}

	public function register() {
		//        $this->mergeConfigFrom(__DIR__ . '/../../config/response-formatter.php', 'response-formatter');
	}
}
