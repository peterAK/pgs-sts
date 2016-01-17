<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassStudent;

use PGS\CoreDomainBundle\Model\SchoolClassStudent\om\BaseSchoolClassStudent;

class SchoolClassStudent extends BaseSchoolClassStudent
{
    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->getStudent()->getFirstName().' '.$this->getStudent()->getLastName());
    }
}
