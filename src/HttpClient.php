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

/**
 * Class HttpClient
 *
 * A simple HTTP client implementation using cURL.
 */
class HttpClient implements ClientInterface
{
    /**
     * @var ResponseFactoryInterface $responseFactory The response factory used to create HTTP responses.
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var resource $curlHandle The cURL handle.
     */
    public $curlHandle;

    /**
     * HttpClient constructor.
     *
     * @param ResponseFactoryInterface $responseFactory The response factory used to create HTTP responses.
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->curlHandle = curl_init();
    }

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request The PSR-7 request to send.
     * @return ResponseInterface The PSR-7 response received.
     * @throws ClientCouldNotProcessException If a cURL error occurs.
     */
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

    /**
     * Destructor to close the cURL handle.
     */
    public function __destruct()
    {
        if ($this->curlHandle) {
            curl_close($this->curlHandle);
        }
    }
}
