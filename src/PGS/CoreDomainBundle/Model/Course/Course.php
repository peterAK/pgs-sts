<?php

namespace PGS\CoreDomainBundle\Model\Course;

use PGS\CoreDomainBundle\Model\Course\om\BaseCourse;

class Course extends BaseCourse
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
