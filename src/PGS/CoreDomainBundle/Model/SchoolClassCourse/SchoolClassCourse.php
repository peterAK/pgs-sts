<?php

namespace PGS\CoreDomainBundle\Model\SchoolClassCourse;

use PGS\CoreDomainBundle\Model\SchoolClassCourse\om\BaseSchoolClassCourse;

class SchoolClassCourse extends BaseSchoolClassCourse
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
