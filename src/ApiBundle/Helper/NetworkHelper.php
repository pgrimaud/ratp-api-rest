<?php
namespace ApiBundle\Helper;

class NetworkHelper
{
    /**
     * @param $value
     * @param bool $reverse
     * @return mixed|null
     */
    public static function typeSlug($value, $reverse = false)
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

        return isset($names[$value]) ? $names[$value] : null;
    }

    /**
     * @param $value
     * @param bool $reverse
     * @return mixed|null
     */
    public static function typeSlugSchedules($value, $reverse = false)
    {
        $names = [
            'metros'     => 'M',
            'rers'       => 'R',
            'tramways'   => 'T',
            'bus'        => 'B',
            'noctiliens' => 'N'
        ];

        if ($reverse) {
            $names = array_flip($names);
        }

        return isset($names[$value]) ? $names[$value] : null;
    }

    /**
     * @param $value
     * @return mixed|string
     */
    public static function forcePrefix($value)
    {
        $names = [
            'tramways'   => 'T',
            'noctiliens' => 'N'
        ];

        return isset($names[$value]) ? $names[$value] : '';
    }
}
