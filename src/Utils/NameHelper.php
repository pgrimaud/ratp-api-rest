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
            'busratp'       => 'buses',
            'noctilienratp' => 'noctiliens'
        ];

        if ($reverse) {
            $names = array_flip($names);
        }

        return $names[$value] ?? '';
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function networkPrefix(string $value): string
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

    /**
     * @param string $value
     *
     * @return string
     */
    public static function clean(string $value): string
    {
        return strtolower(str_replace('+', ' ', $value));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function typeSlugSchedules(string $value): string
    {
        $names = [
            'metros'     => 'M',
            'rers'       => 'R',
            'tramways'   => 'BT',
            'buses'      => 'B',
            'noctiliens' => 'BN'
        ];

        return $names[$value] ?? '';
    }
}
