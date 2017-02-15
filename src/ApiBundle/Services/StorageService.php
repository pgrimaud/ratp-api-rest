<?php
namespace ApiBundle\Services;

class StorageService
{
    /**
     * @param $string
     * @return string
     */
    public function getHash($string)
    {
        return md5($string);
    }
}