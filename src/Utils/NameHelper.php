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
     * @param bool $reverse
     *
     * @return string
     */
    public static function typeSlug(string $value, bool $reverse = false): string
    {
        $names = [
            'metro'         => 'metros',
            'rer'           => 'rers',
            'tram'          => 'tramways',
            'busratp'       => 'bus',
            'noctilienratp' => 'noctiliens'
        ];

        if ($reverse) {
            $names = array_flip($names);
        }

        return $names[$value] ?? '';
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function networkPrefix($value): string
    {
        $names = [
            'noctiliens' => 'N',
            'tramways'   => 'T',
        ];

        return $names[$value] ?? '';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function slugify(string $value): string
    {
        return strtolower(str_replace([' ', '-'], '+', $value));
    }
}
