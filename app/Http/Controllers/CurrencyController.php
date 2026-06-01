<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class CurrencyController extends Controller
{
    private const SUPPORTED_CURRENCIES = ["IDR", "USD", "SGD", "JPY"];

    public function currency(Request $request, CurrencyService $currency)
    {
        $validated = $request->validate([
            "amount" => "nullable|numeric|min:0",
            "base" => [
                "nullable",
                "string",
                Rule::in(self::SUPPORTED_CURRENCIES),
            ],
            "symbols" => "nullable|string",
        ]);

        $amount = isset($validated["amount"])
            ? (float) $validated["amount"]
            : 1.0;
        $base = strtoupper($validated["base"] ?? "IDR");
        $symbols = $this->parseSymbols($validated["symbols"] ?? null, $base);

        try {
            $result = $currency->latest($base, $symbols, $amount);
        } catch (RuntimeException $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "Gagal mengambil data kurs: " . $e->getMessage(),
                ],
                502,
            );
        }

        return response()->json([
            "success" => true,
            "data" => [
                "provider" => "Frankfurter",
                "amount" => $result["amount"],
                "base" => $result["base"],
                "date" => $result["date"],
                "rates" => $result["rates"],
            ],
        ]);
    }

    /**
     * @return list<string>
     */
    private function parseSymbols(?string $symbols, string $base): array
    {
        if ($symbols === null || trim($symbols) === "") {
            return array_values(
                array_filter(
                    self::SUPPORTED_CURRENCIES,
                    fn(string $currency) => $currency !== $base,
                ),
            );
        }

        return collect(explode(",", $symbols))
            ->map(fn(string $symbol) => strtoupper(trim($symbol)))
            ->filter(
                fn(string $symbol) => in_array(
                    $symbol,
                    self::SUPPORTED_CURRENCIES,
                    true,
                ),
            )
            ->reject(fn(string $symbol) => $symbol === $base)
            ->unique()
            ->values()
            ->all();
    }
}
