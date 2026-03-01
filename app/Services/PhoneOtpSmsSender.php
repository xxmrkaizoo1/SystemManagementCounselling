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
        $messagingServiceSid = (string) config('services.twilio.messaging_service_sid');

        if ($accountSid === '' || $authToken === '') {
            throw new RuntimeException('Twilio account credentials are not configured.');
        }

        if ($fromNumber === '' && $messagingServiceSid === '') {
            throw new RuntimeException('Set TWILIO_FROM_NUMBER or TWILIO_MESSAGING_SERVICE_SID for SMS delivery.');
        }

        $toNumber = $this->normalizePhoneNumber($phone);

        if (! $toNumber) {
            throw new RuntimeException('Phone number format is invalid for SMS delivery.');
        }
        $payload = [
            'To' => $toNumber,
            'Body' => "Your CollegeCare OTP code is {$otp}. This code expires in {$ttlMinutes} minutes.",
        ];

        if ($messagingServiceSid !== '') {
            $payload['MessagingServiceSid'] = $messagingServiceSid;
        } else {
            $payload['From'] = $fromNumber;
        }

        $response = Http::asForm()
            ->withBasicAuth($accountSid, $authToken)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", $payload);

        if (! $response->successful()) {
            $errorMessage = $response->json('message') ?: $response->body();
            $errorCode = $response->json('code');
            $suffix = $errorCode ? " (Twilio code: {$errorCode})" : '';

            throw new RuntimeException('Twilio SMS API request failed: ' . $errorMessage . $suffix);
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
