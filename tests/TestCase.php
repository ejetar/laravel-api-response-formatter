<?php

use Orchestra\Testbench\TestCase as ST;

class TestCase extends ST {
    protected function setUp(): void {
        parent::setUp();
    }

    protected function getPackageProviders($app) {
        return [
            \Ejetar\ApiResponseFormatter\Providers\AppServiceProvider::class
        ];
    }

	public function jsonProvider() {
		return '{name:"Guilherme",sobrenome:"Girardi"}';
	}

	/**
	 * @test
	 * @dataProvider jsonProvider
	 */
    public function test_json($json) {
		//
    }

    /** @test */
    public function test_xml() {
		//
    }

    /** @test */
    public function test_csv() {
		//
    }

    /** @test */
    public function test_yaml() {
		//
    }
}
