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

    /**
     * Get all variable names.
     */
    public function getVariableNames()
    {
        $names = array();
        $items = $this->dom->documentElement->childNodes;
        /** @var \DOMElement $item */
        foreach ($items as $item) {
            $names[] = $item->getAttribute('name');
        }
        return $names;
    }

    /**
     * Get variable value by name.
     *
     * @param $name
     * @return string
     */
    public function getValueByName($name)
    {
        $variable = $this->getVariableDomElementByName($name);
        return $variable->getAttribute('value');
    }

    /**
     * Set variable value.
     *
     * @param $name
     * @param $value
     */
    public function setValue($name, $value)
    {
        $variable = $this->getVariableDomElementByName($name);
        $variable->setAttribute('value', $value);
    }

    /**
     * Set variable imgXML
     *
     * @param $name
     * @param $imgXML
     */
    public function setImgXML($name, $imgXML)
    {
        $variable = $this->getVariableDomElementByName($name);
        $variable->setAttribute('imgXML', $imgXML);
    }

    /**
     * Get item by name attribute.
     *
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
