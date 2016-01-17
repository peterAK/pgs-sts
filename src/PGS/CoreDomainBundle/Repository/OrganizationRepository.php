<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Repository;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\om\BaseOrganizationQuery;
use PGS\CoreDomainBundle\Model\UserProfile;

/**
 * @Service("pgs.core.repository.organization")
 */
class OrganizationRepository extends BaseOrganizationQuery
{
    /**
     * @param int         $organizationId
     * @param UserProfile $userProfile
     *
     * @return Organization
     */
    public function getDefault($organizationId = null, UserProfile $userProfile)
    {
        if (!$organization = $this->create()->findOneByUserId($userProfile->getId())) {
            $organization = new Organization();
        }

        return $organization;
    }
}
