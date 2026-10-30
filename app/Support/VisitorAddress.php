<?php

namespace App\Support;

use Illuminate\Http\Request;

final class VisitorAddress
{
    /**
     * Address used only for rate limits. The nearest public address in
     * X-Forwarded-For is kept, so a visitor cannot pick the left-most value.
     * The rest of the application still uses the direct connection address.
     */
    public static function forRateLimit(Request $request): string
    {
        $forwarded = $request->headers->get('X-Forwarded-For');

        if (is_string($forwarded) && $forwarded !== '') {
            $parts = array_reverse(array_map(trim(...), explode(',', $forwarded)));

            foreach ($parts as $part) {
                if (filter_var($part, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $part;
                }
            }
        }

        return $request->ip() ?: 'unknown';
    }
}
