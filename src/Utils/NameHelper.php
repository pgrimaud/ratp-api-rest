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

        if (!isset($names[$value])) {
            return $value;
        } else {
            return $names[$value];
        }
    }
}
