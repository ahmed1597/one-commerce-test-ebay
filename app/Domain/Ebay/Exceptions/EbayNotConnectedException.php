<?php

namespace App\Domain\Ebay\Exceptions;

use RuntimeException;

final class EbayNotConnectedException extends RuntimeException
{
    public static function make(): self
    {
        return new self('eBay is not connected yet.');
    }
}
