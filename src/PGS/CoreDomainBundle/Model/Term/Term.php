<?php

namespace PGS\CoreDomainBundle\Model\Term;

use PGS\CoreDomainBundle\Model\Term\om\BaseTerm;

class Term extends BaseTerm
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
