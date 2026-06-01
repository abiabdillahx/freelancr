<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class CurrencyService
{
    /**
     * @param  list<string>  $symbols
     * @return array{
     *     amount: float,
     *     base: string,
     *     date: string|null,
     *     rates: array<string, float>
     * }
     */
    public function latest(string $base, array $symbols, float $amount): array
    {
        $base = strtoupper($base);
        $symbols = array_values(array_unique(array_map('strtoupper', $symbols)));
        $symbols = array_values(array_filter($symbols, fn (string $symbol) => $symbol !== $base));

        if ($symbols === []) {
            return [
                'amount' => $amount,
                'base' => $base,
                'date' => null,
                'rates' => [],
            ];
        }

        $response = Http::timeout(10)->get(config('services.frankfurter.url') . '/latest', [
            'amount' => $amount,
            'base' => $base,
            'symbols' => implode(',', $symbols),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Frankfurter request failed: HTTP ' . $response->status());
        }

        $data = $response->json();
        $rates = $data['rates'] ?? [];

        if (! is_array($rates)) {
            throw new RuntimeException('Frankfurter returned an invalid response.');
        }

        return [
            'amount' => (float) ($data['amount'] ?? $amount),
            'base' => (string) ($data['base'] ?? $base),
            'date' => $data['date'] ?? null,
            'rates' => collect($rates)
                ->map(fn ($value) => round((float) $value, 6))
                ->all(),
        ];
    }
}
