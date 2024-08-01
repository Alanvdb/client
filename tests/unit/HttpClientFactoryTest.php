<?php declare(strict_types=1);

namespace AlanVdb\Tests\Http;

use PHPUnit\Framework\TestCase;
use AlanVdb\Http\Factory\HttpClientFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use AlanVdb\Http\HttpClient;

class HttpClientFactoryTest extends TestCase
{
    public function testCreateClient()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $factory = new HttpClientFactory($responseFactory);
        $client = $factory->createClient();
        
        $this->assertInstanceOf(ClientInterface::class, $client);
        $this->assertInstanceOf(HttpClient::class, $client);
    }
}
