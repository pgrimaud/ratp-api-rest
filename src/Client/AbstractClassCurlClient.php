<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;

abstract class AbstractClassCurlClient
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }
}
