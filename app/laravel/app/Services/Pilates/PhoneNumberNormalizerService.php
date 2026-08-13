<?php

namespace App\Services\Pilates;

class PhoneNumberNormalizerService
{
    public function normalize(string $value): string
    {
        $value = mb_convert_kana($value, 'n');
        $value = preg_replace('/[\x{2010}-\x{2015}\x{2212}\x{FF0D}\x{30FC}-]/u', '', $value);
        return trim($value);
    }
}