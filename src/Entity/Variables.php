<?php

namespace Galilee\PPM\SDK\Chili\Entity;

class Variables extends AbstractEntity
{
    protected $variables = array();

    public function setDomFromXmlString($xmlString)
    {
        parent::setDomFromXmlString($xmlString);
        $this->dom->documentElement->setAttribute('savedInEditor', 'false');
    }

    public function getVariables()
    {
        $items = $this->dom->documentElement->childNodes;
        /** @var \DOMElement $item */
        foreach ($items as $item)
        {
            echo $item->getAttribute('name') . '<br />';
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function getValueByName($name)
    {
        $variable = $this->getVariableDomElementByName($name);
        return $variable->getAttribute('value');
    }

    public function setValue($name, $value)
    {
        $variable = $this->getVariableDomElementByName($name);
        $variable->setAttribute('value', $value);
    }

    /**
     * @param $name
     * @return \DOMElement
     */
    public function getVariableDomElementByName($name)
    {
        $xpath = new \DOMXPath($this->dom);
        $query = '//item/@name[. = "' . $name . '"]';
        $entries = $xpath->query($query);
        /** @var \DOMAttr $entry */
        $entry = $entries->item(0);
        return $entry->ownerElement;
    }
}
