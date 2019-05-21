<?php

declare(strict_types=1);

namespace App\Utils;

class NameHelper
{
    /**
     * @param string $value
     *
     * @return string
     */
    public static function statusSlug(string $value): string
    {
        $names = [
            'Travaux'              => 'normal_trav',
            'Incidents techniques' => 'critical',
            'Incidents'            => 'critical',
        ];

        return $names[$value] ?? $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function typeSlug(string $value): string
    {
        $names = [
            'metro'         => 'metros',
            'rer'           => 'rers',
            'tram'          => 'tramways',
            'busratp'       => 'bus',
            'noctilienratp' => 'noctiliens'
        ];

        return $names[$value] ?? '';
    }
}
