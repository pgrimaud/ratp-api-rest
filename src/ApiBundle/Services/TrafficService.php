<?php
namespace ApiBundle\Services;

class TrafficService
{
    public function getAllTraffic()
    {
        return [
            'payload' => 'NEW TRAFFIC' . date('Y-m-d H:i:s')
        ];
    }
}