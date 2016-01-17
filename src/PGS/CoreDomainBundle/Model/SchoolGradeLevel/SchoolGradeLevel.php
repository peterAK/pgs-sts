<?php

namespace PGS\CoreDomainBundle\Model\SchoolGradeLevel;

use PGS\CoreDomainBundle\Model\SchoolGradeLevel\om\BaseSchoolGradeLevel;

class SchoolGradeLevel extends BaseSchoolGradeLevel
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getGradeLevel()->getGrade()->getName();
    }
}
