# HttpClient

A basic PHP client implementation using cURL, adhering to PSR standards.

## Overview

The `HttpClient` library provides a simple and extensible HTTP client interface, implementing the PSR-18 `ClientInterface`. It allows you to send HTTP requests and receive responses in a standardized way.

## Features

- Simple and easy-to-use API
- PSR-18 compliant
- Uses cURL for making HTTP requests
- Supports various HTTP methods (GET, POST, PUT, DELETE, etc.)
- Customizable HTTP headers and request bodies
- Handles SSL verification

## Installation

To install the `HttpClient` library, use Composer:

```sh
composer require alanvdb/http-client
```

## Usage

Here is an example of how to use the `HttpClient`:

```php
<?php

require 'vendor/autoload.php';

use AlanVdb\Http\HttpClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ResponseFactory;

$responseFactory = new ResponseFactory();
$client = new HttpClient($responseFactory);

$request = new Request('GET', 'https://api.example.com/data');
$response = $client->sendRequest($request);

echo $response->getBody();
```

## Testing

To run the tests, use PHPUnit. Ensure you have PHPUnit installed and execute the following command:

```sh
vendor/bin/phpunit
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Issues and Feedback

If you encounter any issues or have feedback, please open an issue on the [GitHub repository](https://github.com/alanvdb/http-client/issues).
