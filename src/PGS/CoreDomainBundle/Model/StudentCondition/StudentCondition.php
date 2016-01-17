<?php

namespace PGS\CoreDomainBundle\Model\StudentCondition;

use PGS\CoreDomainBundle\Model\StudentCondition\om\BaseStudentCondition;

class StudentCondition extends BaseStudentCondition
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
