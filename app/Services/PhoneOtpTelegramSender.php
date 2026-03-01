<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PhoneOtpTelegramSender
{
    public function send(string $phone, int $otp, int $ttlMinutes): void
    {
        $endpoint = trim((string) config('services.telegram.otp_endpoint'));
        $botToken = trim((string) config('services.telegram.bot_token'));
        $chatId = trim((string) config('services.telegram.chat_id'));
        $authToken = trim((string) config('services.telegram.auth_token'));
        $message = "Your CollegeCare OTP code is {$otp}. This code expires in {$ttlMinutes} minutes.";
        // Prefer Telegram Bot API whenever bot token + chat ID are configured.
        // This avoids accidental fallback to an outdated custom endpoint configuration.
        if ($botToken !== '' && $chatId !== '') {
            $this->sendViaTelegramBotApi($endpoint, $botToken, $chatId, $message);
            return;
        }

        if ($endpoint === '') {
            if ($botToken !== '' && $chatId === '') {
                throw new RuntimeException('Telegram chat ID is not configured. Set TELEGRAM_CHAT_ID in your .env file.');
            }

            throw new RuntimeException('Telegram OTP endpoint is not configured. Set TELEGRAM_OTP_ENDPOINT or configure TELEGRAM_BOT_TOKEN + TELEGRAM_CHAT_ID.');
        }

        $toNumber = $this->normalizePhoneNumber($phone);

        if (! $toNumber) {
            throw new RuntimeException('Phone number format is invalid for Telegram delivery.');
        }
        $payload = [
            'phone' => $toNumber,
            'otp' => $otp,
            'ttl_minutes' => $ttlMinutes,
            'message' => $message,
        ];

        $request = Http::acceptJson();

        if ($authToken !== '') {
            $request = $request->withToken($authToken);
        }

        $response = $request->post($endpoint, $payload);

        if (! $response->successful()) {
            $errorMessage = $response->json('message')
                ?: $response->json('error.message')
                ?: $response->body();

            throw new RuntimeException('Telegram OTP API request failed: ' . $errorMessage);
        }
    }


    private function sendViaTelegramBotApi(string $endpoint, string $botToken, string $chatId, string $message): void
    {
        $resolvedEndpoint = $endpoint;

        if ($resolvedEndpoint === '' || ! str_contains($resolvedEndpoint, 'api.telegram.org')) {
            $resolvedEndpoint = "https://api.telegram.org/bot{$botToken}/sendMessage";
        }

        if (str_contains($resolvedEndpoint, '{bot_token}')) {
            $resolvedEndpoint = str_replace('{bot_token}', $botToken, $resolvedEndpoint);
        }

        $response = Http::acceptJson()->post($resolvedEndpoint, [
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        if (! $response->successful() || $response->json('ok') === false) {
            $errorMessage = $response->json('description')
                ?: $response->json('message')
                ?: $response->body();

            throw new RuntimeException('Telegram Bot API request failed: ' . $errorMessage);
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
