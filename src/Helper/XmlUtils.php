<?php

namespace Galilee\PPM\SDK\Chili\Helper;

class XmlUtils
{

    /**
     * @param $xmlString
     * @return \DOMDocument
     */
    static public function stringToDomDocument($xmlString = '')
    {
        $dom = new \DOMDocument();
        $dom->formatOutput = true;
        if ($xmlString) {
            $dom->loadXML($xmlString);
        }
        return $dom;
    }

    static public function dump($xmlString)
    {
        echo "<pre>";
        echo htmlspecialchars($xmlString);
        echo "</pre>";
    }
}