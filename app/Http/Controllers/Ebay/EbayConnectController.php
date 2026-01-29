<?php

namespace App\Http\Controllers\Ebay;

use App\Http\Controllers\Controller;
use App\Infrastructure\Ebay\Auth\EbayOAuthClient;
use App\Infrastructure\Ebay\Repositories\EbayConnectionRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class EbayConnectController extends Controller
{
    public function __construct(
        private EbayOAuthClient $oauth,
        private EbayConnectionRepository $connections,
    ) {}

    public function redirect(): RedirectResponse
    {
        $state = (string) Str::uuid();
        session(['ebay_oauth_state' => $state]);

        return redirect()->away($this->oauth->buildConsentUrl($state));
    }

    public function callback(Request $request): RedirectResponse
    {
        $state = $request->string('state')->toString();
        $code = $request->string('code')->toString();

        if ($state === '' || $state !== (string) session('ebay_oauth_state')) {
            abort(419, 'Invalid OAuth state.');
        }

        if ($code === '') {
            abort(400, 'Missing authorization code.');
        }

        $payload = $this->oauth->exchangeCodeForToken($code);
        $this->connections->createFromTokenPayload($payload);

        return redirect('/')->with('status', 'eBay connected successfully.');
    }
}
