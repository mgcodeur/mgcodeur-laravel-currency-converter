<?php

namespace Mgcodeur\CurrencyConverter\Traits;

use Mgcodeur\CurrencyConverter\Exceptions\MissingAmountException;
use Mgcodeur\CurrencyConverter\Exceptions\MissingCurrencyException;
use Mgcodeur\CurrencyConverter\Exceptions\NetworkException;

trait CurrencyConverterManager
{
    /**
     * @throws NetworkException | MissingAmountException | MissingCurrencyException
     */
    public function get($format = false): float|int|array|string
    {
        $this->verifyDataBeforeGettingResults();

        if ($this->currencies) {
            return $this->currencies;
        }

        $response = $this->currencyService->runConversionFrom(
            from: $this->from,
            to: $this->to
        );

        if ($response->failed() || ! $response->json()) {
            throw new NetworkException();
        }

        $result = $response->json();

        if (! $this->to) {
            return $this->currencyService->convertAllCurrency(
                amount: $this->amount,
                from: $this->from,
                result: $result,
                format: $format
            );
        }

        if ($format) {
            return number_format(
                num: $result[$this->to] * $this->amount,
                decimals: config('currency-converter.currency.format.decimals'),
                decimal_separator: config('currency-converter.currency.format.decimal_separator'),
                thousands_separator: config('currency-converter.currency.format.thousand_separator')
            );
        } else {
            return $result[$this->to] * $this->amount;
        }
    }

    /**
     * @throws NetworkException | MissingAmountException | MissingCurrencyException
     */
    public function format(): float|array|int|string
    {
        return $this->get(true);
    }

    /**
     * @throws MissingAmountException | MissingCurrencyException
     */
    private function verifyDataBeforeGettingResults(): void
    {
        if (! $this->amount && ! $this->currencies) {
            throw new MissingAmountException();
        }
        if (! $this->from && ! $this->currencies) {
            throw new MissingCurrencyException();
        }
    }
}
