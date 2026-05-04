<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpLocationService
{
    protected array $cache = [];

    public function resolve(string $ip): array
    {
        if (isset($this->cache[$ip])) {
            return $this->cache[$ip];
        }

        try {
            $response = Http::timeout(2)->get("https://ip-api.com/json/{$ip}?lang=ru");

            if ($response->successful()) {
                $data = $response->json();
                $result = [
                    'city' => $data['city'] ?? null,
                    'country' => $data['country'] ?? null,
                ];
                $this->cache[$ip] = $result;

                return $result;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to resolve IP location', [
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);
        }

        $this->cache[$ip] = ['city' => null, 'country' => null];

        return ['city' => null, 'country' => null];
    }
}
