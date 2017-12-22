<?php
namespace RatpApi\ApiBundle\Helper;

use JMS\Serializer\Annotation\XmlKeyValuePairs;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * @XmlRoot("response")
 */
class XmlResponseHelper
{
    /**
     * @var array
     * @XmlKeyValuePairs
     */
    private $result;

    /**
     * @var array
     * @XmlKeyValuePairs
     */
    private $_metadata;

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->_metadata = $metadata;
    }
}
