<?php

namespace PGS\CoreDomainBundle\Model\SchoolTerm;

use PGS\CoreDomainBundle\Model\SchoolTerm\om\BaseSchoolTerm;

class SchoolTerm extends BaseSchoolTerm
{
    /**
     * @return string
     */
    public function __toString()
    {
        return
            $this->getSchoolYear()->getAcademicYear()->getYearStart().'-'.
            $this->getSchoolYear()->getAcademicYear()->getYearEnd().': '.
            $this->getTerm()->getName();
    }

}
