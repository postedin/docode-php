<?php

namespace Postedin\Docode\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Postedin\Docode\DocodeApi;
use GuzzleHttp\Psr7;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

abstract class UnitTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    protected function docodeApi(MockHandler $mockHandler = null, $token = 'fake_valid_token')
    {
        $options = [];

        if (! is_null($mockHandler)) {
            $options['guzzleOptions']['handler'] = $mockHandler;
        }

        return new DocodeApi($token, $options);
    }

    protected function mockResponseHandler(array $responses)
    {
        return new MockHandler($responses);
    }

    protected function mockResponse($code = 200, $content = '', array $headers = [])
    {
        $body = Psr7\stream_for(json_encode($content));

        $headers = array_merge(['Content-Type' => 'application/json'], $headers);

        return new Psr7\Response($code, $headers, $body);
    }
}
