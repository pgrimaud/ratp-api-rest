<?php

declare(strict_types=1);

namespace App\Service\Ratp;

use App\Utils\NameHelper;
use Ratp\{Api, Line, Lines};

class RatpLinesService extends AbstractRatpService implements RatpServiceInterface
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        $return = [];

        $lines = new Lines();
        $api   = new Api();

        foreach ($api->getLines($lines)->getReturn() as $line) {
            /** @var Line $line */
            if ($line instanceof Line) {
                $type = NameHelper::typeSlug($line->getReseau()->getCode());

                if ($type !== '') {
                    $return[$type][] = [
                        'code'       => str_replace(['N', 'T'], '', $line->getCode()),
                        'name'       => $line->getReseau()->getName() . ' ' . $line->getCode(),
                        'directions' => $line->getName(),
                        'id'         => $line->getId(),
                    ];
                }
            }
        }

        return $return;
    }
}
