<?php

namespace Galilee\PPM\SDK\Chili\Entity;

class SearchResult extends AbstractEntity
{

    public function getCount()
    {
        return $this->dom->documentElement->childNodes->length;
    }
}
