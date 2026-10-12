<?php

namespace App\Support;

// Converts an integer amount into its Indonesian terbilang (spelled-out) words,
// used to render the "Terbilang : ..." line on invoices.
class Terbilang
{
    private const ONES = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas',
    ];

    public static function make(int $number): string
    {
        if ($number < 0) {
            return 'minus '.self::make(abs($number));
        }

        if ($number < 12) {
            return self::ONES[$number];
        }

        if ($number < 20) {
            return trim(self::make($number - 10).' belas');
        }

        if ($number < 100) {
            return trim(self::make(intdiv($number, 10)).' puluh '.self::make($number % 10));
        }

        if ($number < 200) {
            return trim('seratus '.self::make($number - 100));
        }

        if ($number < 1000) {
            return trim(self::make(intdiv($number, 100)).' ratus '.self::make($number % 100));
        }

        if ($number < 2000) {
            return trim('seribu '.self::make($number - 1000));
        }

        if ($number < 1_000_000) {
            return trim(self::make(intdiv($number, 1000)).' ribu '.self::make($number % 1000));
        }

        if ($number < 1_000_000_000) {
            return trim(self::make(intdiv($number, 1_000_000)).' juta '.self::make($number % 1_000_000));
        }

        if ($number < 1_000_000_000_000) {
            return trim(self::make(intdiv($number, 1_000_000_000)).' miliar '.self::make($number % 1_000_000_000));
        }

        return trim(self::make(intdiv($number, 1_000_000_000_000)).' triliun '.self::make($number % 1_000_000_000_000));
    }

    /**
     * Spells out a Rupiah amount, e.g. 100000000.0 -> "Seratus Juta Rupiah".
     */
    public static function rupiah(float $amount): string
    {
        $rounded = (int) round($amount);

        if ($rounded === 0) {
            return 'Nol Rupiah';
        }

        return ucwords(self::make($rounded)).' Rupiah';
    }
}
