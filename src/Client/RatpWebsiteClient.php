<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Exception\GuzzleException;

class RatpWebsiteClient extends AbstractClassCurlClient
{
    const ENDPOINT = 'https://www.ratp.fr/meteo/ajax/data';

    /**
     * @return array
     */
    public function getData(): array
    {
        try {
            $response = $this->client->request('GET', self::ENDPOINT, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'
                ],
                'timeout' => 10,
            ]);

            return $this->formatData(json_decode($response->getBody()->getContents(), true));
        } catch (GuzzleException $e) {
            return [];
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function formatData(array $data): array
    {
        $results = [];

        $matchingRessources = [
            'metros'   => 'metro',
            'rers'     => 'rer',
            'tramways' => 'tram'
        ];

        foreach ($matchingRessources as $route => $ratp_route) {
            $dataName = $matchingRessources[$route];

            foreach ($data['status'][$dataName]['lines'] as $line => $value) {
                $line = strtolower(str_replace(['T', 'M', 'R'], '', $line));

                $results[$route][] = [
                    'line'    => strtoupper($line),
                    'slug'    => $value['name'],
                    'title'   => $value['title'],
                    'message' => $value['message']
                ];
            }
        }

        return $results;
    }
}
