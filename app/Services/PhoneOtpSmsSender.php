<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PhoneOtpSmsSender
{
    public function send(string $phone, int $otp, int $ttlMinutes): void
    {
        $accountSid = (string) config('services.twilio.sid');
        $authToken = (string) config('services.twilio.auth_token');
        $fromNumber = (string) config('services.twilio.from');

        if ($accountSid === '' || $authToken === '' || $fromNumber === '') {
            throw new RuntimeException('Twilio SMS credentials are not configured.');
        }

        $toNumber = $this->normalizePhoneNumber($phone);

        if (! $toNumber) {
            throw new RuntimeException('Phone number format is invalid for SMS delivery.');
        }

        $response = Http::asForm()
            ->withBasicAuth($accountSid, $authToken)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => $fromNumber,
                'To' => $toNumber,
                'Body' => "Your CollegeCare OTP code is {$otp}. This code expires in {$ttlMinutes} minutes.",
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Twilio SMS API request failed: ' . $response->body());
        }
    }

    private function normalizePhoneNumber(string $phone): ?string
    {
        $sanitized = preg_replace('/[^\d+]/', '', trim($phone)) ?? '';

        if ($sanitized === '') {
            return null;
        }

        if (str_starts_with($sanitized, '00')) {
            $sanitized = '+' . substr($sanitized, 2);
        }

        if (str_starts_with($sanitized, '0')) {
            $defaultCountryCode = (string) config('services.twilio.default_country_code', '+60');
            $countryPrefix = ltrim($defaultCountryCode, '+');
            $sanitized = '+' . $countryPrefix . substr($sanitized, 1);
        }

        if (! str_starts_with($sanitized, '+')) {
            $sanitized = '+' . ltrim($sanitized, '+');
        }

        if (! preg_match('/^\+[1-9]\d{7,14}$/', $sanitized)) {
            return null;
        }

        return $sanitized;
    }
}
