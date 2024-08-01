<?php declare(strict_types=1);

namespace AlanVdb\Tests\Http;

use PHPUnit\Framework\TestCase;
use AlanVdb\Http\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Stream;
use AlanVdb\Http\Exception\InvalidClientParamException;
use AlanVdb\Http\Exception\ClientCouldNotProcessException;

class HttpClientTest extends TestCase
{
    public function testConstructorWithoutCert()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);
        $this->assertInstanceOf(HttpClient::class, $client);
    }

    public function testSendRequest()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);

        $request = $this->createMock(RequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('__toString')->willReturn('https://jsonplaceholder.typicode.com/todos/1');
        $body = new Stream(fopen('php://temp', 'r+'));

        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getHeaders')->willReturn([]);
        $request->method('getBody')->willReturn($body);

        $response = $client->sendRequest($request);

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSendRequestWithoutCertToArchiveOrg()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);
        
        $request = $this->createMock(RequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('__toString')->willReturn('https://archive.org');
        
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write('');
        $body->rewind();
        
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getHeaders')->willReturn([]);
        $request->method('getBody')->willReturn($body);
        
        try {
            $response = $client->sendRequest($request);
            $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (ClientCouldNotProcessException $e) {
            $this->fail('Request to archive.org without SSL cert failed: ' . $e->getMessage());
        }
    }

    public function testSendRequestThrowsClientCouldNotProcessExceptionOnCurlError()
    {
        $this->expectException(ClientCouldNotProcessException::class);

        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);
        $request = $this->createMock(RequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('__toString')->willReturn('http://nonexistent.url');

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write('');
        $body->rewind();

        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');
        $request->method('getHeaders')->willReturn([]);
        $request->method('getBody')->willReturn($body);

        $client->sendRequest($request);
    }

    public function testSendRequestWithPostMethod()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);

        $request = $this->createMock(RequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('__toString')->willReturn('https://jsonplaceholder.typicode.com/posts');
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write('{"title": "foo", "body": "bar", "userId": 1}');
        $body->rewind();

        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getHeaders')->willReturn(['Content-Type' => ['application/json']]);
        $request->method('getBody')->willReturn($body);

        $response = $client->sendRequest($request);

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testSendRequestWithHeaders()
    {
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $client = new HttpClient($responseFactory);

        $request = $this->createMock(RequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('__toString')->willReturn('https://jsonplaceholder.typicode.com/posts');
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write('{"title": "foo", "body": "bar", "userId": 1}');
        $body->rewind();

        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getHeaders')->willReturn([
            'Content-Type' => ['application/json'],
            'Authorization' => ['Bearer token']
        ]);
        $request->method('getBody')->willReturn($body);

        $response = $client->sendRequest($request);

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(201, $response->getStatusCode());
    }
}
