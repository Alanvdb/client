<?php declare(strict_types=1);

namespace AlanVdb\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use GuzzleHttp\Psr7\Stream;
use AlanVdb\Http\Exception\InvalidClientParamException;
use AlanVdb\Http\Exception\ClientCouldNotProcessException;
use GuzzleHttp\Psr7\Response;

class HttpClient implements ClientInterface
{
    private ResponseFactoryInterface $responseFactory;
    public $curlHandle;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->curlHandle = curl_init();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $url = (string) $request->getUri();
        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandle, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);

        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $headers[] = $name . ': ' . $value;
            }
        }
        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);

        if (!in_array($request->getMethod(), ['GET', 'HEAD'])) {
            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, (string) $request->getBody());
        }

        $responseBody = curl_exec($this->curlHandle);

        if (curl_errno($this->curlHandle)) {
            throw new ClientCouldNotProcessException('cURL error: ' . curl_error($this->curlHandle));
        }
        $statusCode = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);

        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($responseBody);
        $stream->rewind();

        return new Response($statusCode, $headers, $stream);
    }

    public function __destruct()
    {
        if ($this->curlHandle) {
            curl_close($this->curlHandle);
        }
    }
}
