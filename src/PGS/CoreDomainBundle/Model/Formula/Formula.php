<?php

namespace PGS\CoreDomainBundle\Model\Formula;

use PGS\CoreDomainBundle\Model\Formula\om\BaseFormula;

class Formula extends BaseFormula
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
