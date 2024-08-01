<?php declare(strict_types=1);

namespace AlanVdb\Http\Definition;

use Psr\Http\Client\ClientInterface;

interface HttpClientFactoryInterface
{
    public function createClient(): ClientInterface;
}
