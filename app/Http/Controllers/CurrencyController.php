<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyController extends Controller
{
    /**
     * Supported currencies for conversion
     */
    private $currencies = [
        'BDT' => 'Bangladeshi Taka (৳)',
        'USD' => 'US Dollar ($)',
        'EUR' => 'Euro (€)',
        'GBP' => 'British Pound (£)',
        'INR' => 'Indian Rupee (₹)',
        'THB' => 'Thai Baht (฿)',
        'MYR' => 'Malaysian Ringgit (RM)',
        'SGD' => 'Singapore Dollar (S$)',
        'JPY' => 'Japanese Yen (¥)',
        'AUD' => 'Australian Dollar (A$)',
        'CAD' => 'Canadian Dollar (C$)',
        'AED' => 'UAE Dirham (د.إ)',
        'SAR' => 'Saudi Riyal (﷼)',
    ];

    /*
    |--------------------------------------------------------------------------
    | Show Currency Converter Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $currencies = $this->currencies;

        // Fetch popular rates (BDT base) — cached for 24 hours
        $popularRates = $this->getPopularRates();

        return view('currency', compact('currencies', 'popularRates'));
    }

    /*
    |--------------------------------------------------------------------------
    | Perform Conversion (AJAX + Form Fallback)
    |--------------------------------------------------------------------------
    */
    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
        ]);

        $amount = $request->amount;
        $from   = strtoupper($request->from);
        $to     = strtoupper($request->to);

        // Cache the exchange rate for 24 hours
        $cacheKey = "exchange_rate_{$from}_{$to}";
        $rateData = Cache::remember($cacheKey, now()->addHours(24), function () use ($from, $to) {
            return $this->fetchRate($from, $to);
        });

        if ($rateData === null) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to fetch exchange rate. Please try again later.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to fetch exchange rate. Please try again later.');
        }

        $convertedAmount = round($amount * $rateData['rate'], 2);

        $result = [
            'amount'           => $amount,
            'from'             => $from,
            'to'               => $to,
            'rate'             => $rateData['rate'],
            'converted_amount' => $convertedAmount,
            'last_updated'     => $rateData['last_updated'] ?? now()->toDateTimeString(),
        ];

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        // Otherwise redirect back with result
        $currencies = $this->currencies;
        $popularRates = $this->getPopularRates();
        return view('currency', compact('currencies', 'popularRates', 'result'));
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Exchange Rate from API
    |--------------------------------------------------------------------------
    */
    private function fetchRate(string $from, string $to): ?array
    {
        $apiKey = config('services.exchangerate.key');

        if (!$apiKey || $apiKey === 'your_exchangerate_api_key_here') {
            // Fallback rates for demo/testing when no API key is configured
            return $this->getFallbackRate($from, $to);
        }

        try {
            $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/pair/{$from}/{$to}";
            $response = Http::timeout(10)->get($url);

            if ($response->successful() && $response->json('result') === 'success') {
                return [
                    'rate'         => $response->json('conversion_rate'),
                    'last_updated' => $response->json('time_last_update_utc'),
                ];
            }
        } catch (\Exception $e) {
            // Fall through to fallback
        }

        return $this->getFallbackRate($from, $to);
    }

    /*
    |--------------------------------------------------------------------------
    | Fallback Rates (for demo without API key)
    |--------------------------------------------------------------------------
    */
    private function getFallbackRate(string $from, string $to): array
    {
        // Approximate rates as of 2026 (BDT base)
        $bdtRates = [
            'BDT' => 1,
            'USD' => 0.0083,
            'EUR' => 0.0076,
            'GBP' => 0.0065,
            'INR' => 0.6917,
            'THB' => 0.2867,
            'MYR' => 0.0367,
            'SGD' => 0.0111,
            'JPY' => 1.2417,
            'AUD' => 0.0128,
            'CAD' => 0.0114,
            'AED' => 0.0305,
            'SAR' => 0.0312,
        ];

        // Convert from source to BDT, then BDT to target
        $fromRate = $bdtRates[$from] ?? null;
        $toRate   = $bdtRates[$to] ?? null;

        if ($fromRate === null || $toRate === null) {
            return ['rate' => 1, 'last_updated' => 'Fallback rates'];
        }

        $rate = $toRate / $fromRate;

        return [
            'rate'         => round($rate, 6),
            'last_updated' => 'Using approximate rates (no API key configured)',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Get Popular Rates (BDT → common currencies)
    |--------------------------------------------------------------------------
    */
    private function getPopularRates(): array
    {
        $popular = ['USD', 'EUR', 'GBP', 'INR', 'THB', 'SGD'];
        $rates = [];

        foreach ($popular as $currency) {
            $cacheKey = "exchange_rate_BDT_{$currency}";
            $rateData = Cache::remember($cacheKey, now()->addHours(24), function () use ($currency) {
                return $this->fetchRate('BDT', $currency);
            });

            if ($rateData) {
                $rates[$currency] = $rateData['rate'];
            }
        }

        return $rates;
    }
}
