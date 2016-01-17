<?php

namespace PGS\CoreDomainBundle\Model\Condition;

use PGS\CoreDomainBundle\Model\Condition\om\BaseCondition;

class Condition extends BaseCondition
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
