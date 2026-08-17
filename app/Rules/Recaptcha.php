<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost'], true);

        $secret = config('services.recaptcha.secret');
        if ((! is_string($secret) || $secret === '') && $isLocalHost) {
            $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
        }

        if (! is_string($secret) || $secret === '') {
            $fail('Captcha is not configured.');

            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the captcha.');

            return;
        }

        $response = Http::asForm()
            ->timeout(8)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

        if (! $response->json('success')) {
            $fail('Please complete the captcha.');
        }
    }
}
