<?php

namespace PGS\CoreDomainBundle\Model\SchoolClass;

use PGS\CoreDomainBundle\Model\SchoolClass\om\BaseSchoolClass;

class SchoolClass extends BaseSchoolClass
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
