<?php

namespace PGS\CoreDomainBundle\Model\Ethnicity;

use PGS\CoreDomainBundle\Model\Ethnicity\om\BaseEthnicity;

class Ethnicity extends BaseEthnicity
{
    public function __toString(){
        return $this->getName();
    }
}
