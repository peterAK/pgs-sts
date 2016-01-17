<?php

namespace PGS\CoreDomainBundle\Model\GradeLevel;

use PGS\CoreDomainBundle\Model\GradeLevel\om\BaseGradeLevel;

class GradeLevel extends BaseGradeLevel
{
    public function __toString()
    {
        return $this->getLevel()->getCurrentTranslation()->getName().' '.$this->getGrade()->getCurrentTranslation()->getName();
    }
}
