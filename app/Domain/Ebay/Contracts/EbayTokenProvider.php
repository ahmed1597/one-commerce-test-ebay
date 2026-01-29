<?php

namespace App\Domain\Ebay\Contracts;

use App\Models\EbayConnection;

interface EbayTokenProvider
{
    public function getValidConnectionOrFail(): EbayConnection;
}
