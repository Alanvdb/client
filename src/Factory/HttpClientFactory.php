<?php declare(strict_types=1);

namespace AlanVdb\Http\Factory;

use AlanVdb\Http\Definition\HttpClientFactoryInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use AlanVdb\Http\HttpClient;

/**
 * Class HttpClientFactory
 *
 * Implements the HttpClientFactoryInterface to create instances of HttpClient.
 */
class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * @var ResponseFactoryInterface $responseFactory The response factory used to create HTTP responses.
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * HttpClientFactory constructor.
     *
     * @param ResponseFactoryInterface $responseFactory The response factory used to create HTTP responses.
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Creates a new instance of HttpClient.
     *
     * @return ClientInterface The created HTTP client instance.
     */
    public function createClient(): ClientInterface
    {
        return new HttpClient($this->responseFactory);
    }
}
