<?php

namespace Galilee\PPM\SDK\Chili\Entity;

/**
 * Class ExportProfile - PDF export settings of a Chili document
 */
class ExportProfile extends AbstractEntity
{
    const RESOURCE_NAME = 'PdfExportSettings';

    protected $name = null;

    protected $availablePropertiesMap = array(
        AbstractEntity::ID    => '/item/@id',
        AbstractEntity::NAME  => '/item/@name',
    );

    /**
     * Get ExportProfile name
     *
     * @return string
     *
     * @throws \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function getName()
    {
        if (!$this->name) {
            $nodeList = $this->get(AbstractEntity::NAME);
            if ($nodeList->length == 1) {
                $this->name = $nodeList->item(0)->nodeValue;
            }
        }

        return $this->name;
    }
}
