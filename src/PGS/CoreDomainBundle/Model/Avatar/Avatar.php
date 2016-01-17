<?php

namespace PGS\CoreDomainBundle\Model\Avatar;

use PGS\CoreDomainBundle\Model\Avatar\om\BaseAvatar;

class Avatar extends BaseAvatar
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
