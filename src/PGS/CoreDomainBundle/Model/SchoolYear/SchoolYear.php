<?php

namespace PGS\CoreDomainBundle\Model\SchoolYear;

use PGS\CoreDomainBundle\Model\SchoolYear\om\BaseSchoolYear;

class SchoolYear extends BaseSchoolYear
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAcademicYear()->getDescription();
    }
}
