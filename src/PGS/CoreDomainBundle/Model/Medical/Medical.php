<?php

namespace PGS\CoreDomainBundle\Model\Medical;

use PGS\CoreDomainBundle\Model\Medical\om\BaseMedical;

class Medical extends BaseMedical
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
