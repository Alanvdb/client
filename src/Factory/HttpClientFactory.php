<?php declare(strict_types=1);

namespace AlanVdb\Http\Factory;

use AlanVdb\Http\Definition\HttpClientFactoryInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use AlanVdb\Http\HttpClient;

class HttpClientFactory implements HttpClientFactoryInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function createClient(): ClientInterface
    {
        return new HttpClient($this->responseFactory);
    }
}
