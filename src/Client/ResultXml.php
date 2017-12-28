<?php
/**
 ** @author Géraud ISSERTES <gissertes@galilee.fr>
 * @copyright © 2014 Galilée (www.galilee.fr)
 */

namespace Galilee\PPM\SDK\Chili\Client;
use Galilee\PPM\SDK\Chili\Helper\Parser;

class ResultXml {
    
    protected $stringResult;


    public function __construct($string) {
        $this->stringResult = $string;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function asSimpleXml() {
        return simplexml_load_string($this->stringResult);
    }

    /**
     * @return \DOMDocument
     */
    public function asDomXml() {
        $dom = new \DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($this->stringResult);
        return $dom;
    }

    /**
     * @return \DOMXPath
     */
    public function asDomXpath() {
        return new \DOMXPath($this->asDomXml());
    }

    /**
     * @return string
     */
    public function asString() {
        return $this->stringResult;
    }

    /**
     * @return array
     */
    public function asArray() {
        return json_decode(json_encode((array) simplexml_load_string($this->stringResult)), 1);
    }

    public function getXpathExpression($expression)
    {
        return Parser::get($this->asString(), $expression);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->stringResult;
    }
}
