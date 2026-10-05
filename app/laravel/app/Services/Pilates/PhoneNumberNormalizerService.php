<?php

namespace App\Services\Pilates;

class PhoneNumberNormalizerService
{
    public function normalize(?string $value): ?string
    {
        $phone = mb_convert_kana((string) $value, 'ns');
        $phone = preg_replace(
            '/[\x{2010}-\x{2015}\x{2212}\x{FF0D}\x{30FC}-]/u',
            '',
            $phone,
        ) ?? '';
        $phone = trim($phone);

        return $phone === '' ? null : $phone;
    }
}
