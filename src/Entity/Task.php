<?php

namespace Galilee\PPM\SDK\Chili\Entity;
use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class Task.
 */
class Task extends AbstractEntity
{
    CONST CHILI_TRUE = 'True';
    CONST CHILI_FALSE = 'False';

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->getAttribute('finished') === self::CHILI_TRUE;
    }

    /**
     * @return bool
     */
    public function isSucceeded()
    {
        return $this->getAttribute('succeeded') === self::CHILI_TRUE;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->getAttribute('path');
    }

    /**
     * @return null|string
     */
    public function getResultUrl()
    {
        return $this->getResult()->getAttribute("url");
    }

    /**
     * @return null|string
     */
    public function getResultPath()
    {
        return $this->getResult()->getAttribute("path");
    }

    /**
     * @return null|string
     */
    public function getResultRelativeUrl()
    {
        return $this->getResult()->getAttribute("relativeURL");
    }

    /**
     * @return \DOMElement
     */
    public function getResult()
    {
        $resultString = $this->getAttribute('result');
        $resultXmlDom = new \DOMDocument();
        $resultXmlDom->loadXML($resultString);
        $xpath = new \DOMXPath($resultXmlDom);
        $result = $xpath->query("//result");
        return $result->item(0);
    }

    /**
     * @return null|string
     */
    public function getRelativeUrl()
    {
        return $this->getAttribute('relativeURL');
    }
}
