<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model\Grade;

use PGS\CoreDomainBundle\Model\Grade\om\BaseGrade;

class Grade extends BaseGrade
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}