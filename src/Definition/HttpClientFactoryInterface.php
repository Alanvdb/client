<?php declare(strict_types=1);

namespace AlanVdb\Http\Definition;

use Psr\Http\Client\ClientInterface;

/**
 * Interface HttpClientFactoryInterface
 *
 * Defines a factory interface for creating HTTP client instances.
 */
interface HttpClientFactoryInterface
{
    /**
     * Creates a new instance of a class that implements ClientInterface.
     *
     * @return ClientInterface The created HTTP client instance.
     */
    public function createClient(): ClientInterface;
}
