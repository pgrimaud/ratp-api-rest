<?php

declare(strict_types=1);

namespace App\Serializer;

use JMS\Serializer\Annotation\XmlKeyValuePairs;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * @XmlRoot("response")
 */
class XmlSerializer
{
    /**
     * @var array
     *
     * @XmlKeyValuePairs
     */
    private array $result;

    /**
     * @var array
     *
     * @XmlKeyValuePairs
     */
    private array $_metadata;

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param array $result
     */
    public function setResult(array $result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata)
    {
        $this->_metadata = $metadata;
    }
}
