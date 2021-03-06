<?php

/**
 * This file is part of the PGS/AdminBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model;

use PGS\CoreDomainBundle\Model\om\BaseState;

class State extends BaseState
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
