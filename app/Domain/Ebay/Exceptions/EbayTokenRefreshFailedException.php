<?php

namespace App\Domain\Ebay\Exceptions;

use RuntimeException;

final class EbayTokenRefreshFailedException extends RuntimeException
{
    public static function make(): self
    {
        return new self('Failed to refresh eBay access token.');
    }
}
