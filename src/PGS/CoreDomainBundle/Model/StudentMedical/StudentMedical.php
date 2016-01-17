<?php

namespace PGS\CoreDomainBundle\Model\StudentMedical;

use PGS\CoreDomainBundle\Model\StudentMedical\om\BaseStudentMedical;

class StudentMedical extends BaseStudentMedical
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
