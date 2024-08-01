<?php declare(strict_types=1);

namespace AlanVdb\Http\Exception;

use AlanVdb\Http\Definition\ClientExceptionInterface;
use InvalidArgumentException;

class InvalidClientParamException
    extends InvalidArgumentException
    implements ClientExceptionInterface
{}