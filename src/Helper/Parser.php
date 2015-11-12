<?php

namespace Galilee\PPM\SDK\Chili\Helper;

use Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException;

/**
 * Class Parser.
 */
class Parser
{
    /**
     * Get xpath expression query result from the given xml.
     *
     * @param string $xml
     * @param string $expression
     *
     * @return \DOMNodeList|null
     *
     * @throws InvalidXpathExpressionException
     */
    public static function get($xml, $expression)
    {
        $nodeList = null;
        $domDoc = self::xmlToDomDoc($xml);

        $xPath = new \DOMXPath($domDoc);
        $nodeList = @$xPath->query($expression);

        if ($nodeList === false) {
            throw new InvalidXpathExpressionException('The expression "'.$expression.'" is not valid for DOMXPath::query()');
        }

        return $nodeList;
    }

    /**
     * Transform an xml string into a \DOMDocument object.
     *
     * @param string $xml
     *
     * @return \DOMDocument
     */
    public static function xmlToDomDoc($xml)
    {
        $domDoc = new \DOMDocument();
        $domDoc->loadXML($xml);

        return $domDoc;
    }
}
