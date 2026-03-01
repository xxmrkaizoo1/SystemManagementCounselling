<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PhoneOtpTelegramSender
{
    public function send(string $phone, int $otp, int $ttlMinutes): void
    {
        $endpoint = (string) config('services.telegram.otp_endpoint');
        $apiKey = (string) config('services.telegram.bot_token');

        if ($endpoint === '') {
            throw new RuntimeException('Telegram OTP endpoint is not configured.');
        }

        $toNumber = $this->normalizePhoneNumber($phone);

        if (! $toNumber) {
            throw new RuntimeException('Phone number format is invalid for Telegram delivery.');
        }
        $payload = [
            'phone' => $toNumber,
            'otp' => $otp,
            'ttl_minutes' => $ttlMinutes,
            'message' => "Your CollegeCare OTP code is {$otp}. This code expires in {$ttlMinutes} minutes.",
        ];

        $request = Http::acceptJson();

        if ($apiKey !== '') {
            $request = $request->withToken($apiKey);
        }

        $response = $request->post($endpoint, $payload);

        if (! $response->successful()) {
            $errorMessage = $response->json('message')
                ?: $response->json('error.message')
                ?: $response->body();

            throw new RuntimeException('Telegram OTP API request failed: ' . $errorMessage);
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
            $defaultCountryCode = (string) config('services.telegram.default_country_code', '+60');
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
