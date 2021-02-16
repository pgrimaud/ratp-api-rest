<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Exception\GuzzleException;

class IxxiApiClient extends AbstractClassCurlClient
{
    const ENDPOINT = 'http://apixha.ixxi.net/APIX?cmd=getTrafficSituation&category=all' .
    '&networkType=all&withText=true&apixFormat=json&keyapp=FvChCBnSetVgTKk324rO';

    public function getData(): array
    {
        try {
            $endpoint = self::ENDPOINT . '&keyapp=' . getenv('API_KEY_IXXI') . '&tmp=' . time();

            $response = $this->client->request('GET', $endpoint, [
                'headers' => [
                    'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 5.0; Google Nexus 4 - 5.0.0 - API 21 - 768x1280 Build/LRX21M)',
                ],
                'timeout' => 10,
            ]);

            return json_decode((string) $response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return [];
        }
    }
}
