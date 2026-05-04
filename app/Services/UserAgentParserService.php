<?php

namespace App\Services;

class UserAgentParserService
{
    protected array $devicePatterns = [
        '/(ipad|tablet)/i' => 'Tablet',
        '/(mobile|android|iphone)/i' => 'Mobile',
    ];

    protected array $browserPatterns = [
        '/Edge\/([0-9]+)/i' => 'Edge',
        '/Chrome\/([0-9]+)/i' => 'Chrome',
        '/Firefox\/([0-9]+)/i' => 'Firefox',
        '/Safari\/([0-9]+)/i' => 'Safari',
    ];

    protected array $osPatterns = [
        '/Windows/i' => 'Windows',
        '/Macintosh|Mac OS X/i' => 'macOS',
        '/Linux/i' => 'Linux',
        '/Android/i' => 'Android',
        '/iPhone|iOS/i' => 'iOS',
    ];

    public function parse(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'device' => 'Unknown',
                'browser' => 'Unknown',
                'os' => 'Unknown',
            ];
        }

        return [
            'device' => $this->parseDevice($userAgent),
            'browser' => $this->parseBrowser($userAgent),
            'os' => $this->parseOs($userAgent),
        ];
    }

    protected function parseDevice(string $ua): string
    {
        foreach ($this->devicePatterns as $pattern => $device) {
            if (preg_match($pattern, $ua)) {
                return $device;
            }
        }

        return 'Desktop';
    }

    protected function parseBrowser(string $ua): string
    {
        // Edge должен проверяться до Chrome (Edge содержит "Chrome" в UA)
        foreach ($this->browserPatterns as $pattern => $browser) {
            if ($browser === 'Safari' && preg_match('/Chrome/i', $ua)) {
                continue;
            }
            if (preg_match($pattern, $ua)) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    protected function parseOs(string $ua): string
    {
        foreach ($this->osPatterns as $pattern => $os) {
            if (preg_match($pattern, $ua)) {
                return $os;
            }
        }

        return 'Unknown';
    }
}
