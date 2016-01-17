<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model\School;

use PGS\CoreDomainBundle\Model\School\om\BaseSchool;

class School extends BaseSchool
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        $statuses = SchoolPeer::getValueSets();

        return $statuses['school.status'];
    }

}
