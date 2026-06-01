<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_currency_endpoint_converts_idr_to_default_symbols(): void
    {
        Http::fake([
            'api.frankfurter.dev/v1/latest*' => Http::response([
                'amount' => 50000.0,
                'base' => 'IDR',
                'date' => '2026-05-26',
                'rates' => [
                    'USD' => 2.8,
                    'SGD' => 3.64,
                    'JPY' => 438.2,
                ],
            ]),
        ]);

        $response = $this->getJson('/api/currency?amount=50000');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.provider', 'Frankfurter')
            ->assertJsonPath('data.amount', 50000)
            ->assertJsonPath('data.base', 'IDR')
            ->assertJsonPath('data.rates.USD', 2.8)
            ->assertJsonPath('data.rates.SGD', 3.64)
            ->assertJsonPath('data.rates.JPY', 438.2);

        Http::assertSent(fn ($request) => str_contains($request->url(), 'amount=50000')
            && str_contains($request->url(), 'base=IDR')
            && str_contains($request->url(), 'symbols=USD%2CSGD%2CJPY'));
    }

    public function test_currency_endpoint_supports_custom_base_and_symbols(): void
    {
        Http::fake([
            'api.frankfurter.dev/v1/latest*' => Http::response([
                'amount' => 100.0,
                'base' => 'USD',
                'date' => '2026-05-26',
                'rates' => [
                    'IDR' => 1785714.285714,
                    'JPY' => 15650.22,
                ],
            ]),
        ]);

        $response = $this->getJson('/api/currency?amount=100&base=USD&symbols=IDR,JPY');

        $response
            ->assertOk()
            ->assertJsonPath('data.base', 'USD')
            ->assertJsonPath('data.rates.IDR', 1785714.285714)
            ->assertJsonPath('data.rates.JPY', 15650.22);
    }

    public function test_currency_endpoint_returns_bad_gateway_when_provider_fails(): void
    {
        Http::fake([
            'api.frankfurter.dev/v1/latest*' => Http::response([], 500),
        ]);

        $response = $this->getJson('/api/currency?amount=50000');

        $response
            ->assertStatus(502)
            ->assertJsonPath('success', false);
    }
}
