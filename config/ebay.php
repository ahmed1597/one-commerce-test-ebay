<?php

return [
    'env' => env('EBAY_ENV', 'sandbox'),

    'client_id' => env('EBAY_CLIENT_ID'),
    'client_secret' => env('EBAY_CLIENT_SECRET'),

    'redirect_uri' => env('EBAY_REDIRECT_URI'),

    'scopes' => env('EBAY_SCOPES', 'https://api.ebay.com/oauth/api_scope/sell.inventory'),
];
