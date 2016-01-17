<?php

namespace PGS\CoreDomainBundle\Model\ParentStudent;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\ParentStudent\om\BaseParentStudent;

class ParentStudent extends BaseParentStudent
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUser()->getUserProfile()->getFirstName()+" "+$this->getUser()->getUserProfile()->getLastName();
    }
}
