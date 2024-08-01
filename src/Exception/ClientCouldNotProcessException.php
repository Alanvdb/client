<?php declare(strict_types=1);

namespace AlanVdb\Http\Exception;

use AlanVdb\Http\Definition\ClientExceptionInterface;
use RuntimeException;

class ClientCouldNotProcessException
    extends RuntimeException
    implements ClientExceptionInterface
{}
