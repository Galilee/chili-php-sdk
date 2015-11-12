<?php

namespace Galilee\PPM\SDK\Chili\Entity;

use Galilee\PPM\SDK\Chili\Helper\Parser;

/**
 * Class Task.
 */
class Task extends AbstractEntity
{
    const STATUS_FINISHED = 'finished';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_PATH = 'path';
    const STATUS_URL = 'url';
    const STATUS_RELATIVE_URL = 'relativeUrl';
    const STATUS_ERROR_MESSAGE = 'errorMessage';
    const STATUS_ERROR_STACK = 'errorStack';

    protected $status = [];

    protected $availablePropertiesMap = [
        AbstractEntity::ID => '/task/@id',
    ];

    /**
     * Get task status information
     *
     * @return array
     *
     * @throws \Galilee\PPM\SDK\Chili\Exception\InvalidXpathExpressionException
     */
    public function getStatus()
    {
        if (empty($this->status)) {
            $queryResult = $this->get('//task');

            if ($queryResult->length == 1) {
                $finished = $queryResult->item(0)->attributes->getNamedItem('finished')->nodeValue;
                $succeeded = $queryResult->item(0)->attributes->getNamedItem('succeeded')->nodeValue;
                $this->status[self::STATUS_FINISHED] = (strtolower($finished) === 'true');
                $this->status[self::STATUS_SUCCEEDED] = (strtolower($succeeded) === 'true');
                $this->status[self::STATUS_ERROR_MESSAGE] =
                    $queryResult->item(0)->attributes->getNamedItem('errorMessage')->nodeValue;
                $this->status[self::STATUS_ERROR_STACK] =
                    $queryResult->item(0)->attributes->getNamedItem('errorStack')->nodeValue;

                if ($this->status[self::STATUS_FINISHED] && $this->status[self::STATUS_SUCCEEDED]) {
                    $result = $queryResult->item(0)->attributes->getNamedItem('result')->nodeValue;

                    if ($result) {
                        // extract attributes from result (the attribute value is also a xml o_O)
                        $queryResult = Parser::get($result, '//result');

                        if ($queryResult->length == 1) {
                            $this->status[self::STATUS_PATH] =
                                $queryResult->item(0)->attributes->getNamedItem('path')->nodeValue;
                            $this->status[self::STATUS_URL] =
                                $queryResult->item(0)->attributes->getNamedItem('url')->nodeValue;
                            $this->status[self::STATUS_RELATIVE_URL] =
                                $queryResult->item(0)->attributes->getNamedItem('relativeURL')->nodeValue;
                        }
                    }
                }
            }
        }

        return $this->status;
    }
}
