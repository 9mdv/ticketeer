<?php

namespace Tests;

use Exception;
use App\Exceptions\Handler;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        TestResponse::macro('assertViewIs', function ($name) {
            Assert::assertEquals($name, $this->original->name());
        });
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(
            ExceptionHandler::class,
            new class extends Handler
            {
                public function __construct()
                { }
                public function report(Exception $e)
                { }
                public function render($request, Exception $e)
                {
                    throw $e;
                }
            }
        );
    }

    public function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }
}
