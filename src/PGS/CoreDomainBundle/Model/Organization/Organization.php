<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model\Organization;

use PGS\CoreDomainBundle\Model\Organization\om\BaseOrganization;

class Organization extends BaseOrganization
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
        $statuses = OrganizationPeer::getValueSets();

        return $statuses['organization.status'];
    }
}
