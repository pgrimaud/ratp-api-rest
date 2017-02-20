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
}
