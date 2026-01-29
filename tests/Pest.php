<?php

use App\Domain\Ebay\Contracts\EbayTokenProvider;
use App\Models\EbayConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

function mockTokenProvider(EbayConnection $conn): EbayTokenProvider
{
    return new class($conn) implements EbayTokenProvider {
        public function __construct(private EbayConnection $conn) {}

        public function getValidConnectionOrFail(): EbayConnection
        {
            return $this->conn;
        }
    };
}
