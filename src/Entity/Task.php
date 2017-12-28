<?php

namespace Galilee\PPM\SDK\Chili\Entity;

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
}
