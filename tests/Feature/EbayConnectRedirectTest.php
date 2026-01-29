<?php

use Illuminate\Support\Facades\Config;

it('redirects to ebay consent page', function () {
    Config::set('ebay.client_id', 'client');
    Config::set('ebay.redirect_uri', 'RuName');
    Config::set('ebay.scopes', 'scope1 scope2');
    Config::set('ebay.env', 'sandbox');

    $res = $this->get('/ebay/connect');

    $res->assertRedirect();
    expect($res->headers->get('Location'))->toContain('https://auth.sandbox.ebay.com/oauth2/authorize');
});
it('handles ebay oauth callback', function () {
    Config::set('ebay.client_id', 'client');
    Config::set('ebay.client_secret', 'secret');
    Config::set('ebay.redirect_uri', 'RuName');
    Config::set('ebay.scopes', 'scope1 scope2');
    Config::set('ebay.env', 'sandbox');

    // Mock the token exchange response
    \Illuminate\Support\Facades\Http::fake([
        'https://api.sandbox.ebay.com/identity/v1/oauth2/token' => \Illuminate\Support\Facades\Http::response([
            'access_token' => 'access-token',
            'expires_in' => 7200,
            'refresh_token' => 'refresh-token',
            'refresh_token_expires_in' => 5184000,
        ], 200),
    ]);
    $this->withSession(['ebay_oauth_state' => 'state-value']);

    $res = $this->get('/ebay/callback?code=auth-code&state=state-value');

    $res->assertRedirect('/');
    $this->assertDatabaseHas('ebay_connections', [
        'access_token' => 'access-token',
        'refresh_token' => 'refresh-token',
    ]);
});
