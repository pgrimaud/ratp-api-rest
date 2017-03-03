<?php
namespace ApiBundle\Helper;

class NamesHelper
{
    /**
     * @param $value
     * @return mixed
     */
    public static function getSlug($value)
    {
        $names = [
            'SNCF'    => 'trains',
            'RER'     => 'rers',
            'Tramway' => 'tramways',
            'Métro'   => 'metros'
        ];

        return $names[$value];
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function statusSlug($value)
    {
        $names = [
            'Travaux'              => 'normal_trav',
            'Incidents techniques' => 'critical'
        ];

        /**
         * @todo LOG IT !!!
         */
        if (!isset($names[$value])) {
            return $value;
        } else {
            return $names[$value];
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function slugify($value)
    {
        return strtolower(str_replace([' ', '-'], '+', $value));
    }
}
