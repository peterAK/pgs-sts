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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\om\BaseSchoolQuery;
use PGS\CoreDomainBundle\Model\UserProfile;

/**
 * @Service("pgs.core.repository.school")
 */
class SchoolRepository extends BaseSchoolQuery
{
    /**
     * @param int               $schoolId
     * @param UserProfile       $userProfile
     * @param Organization|null $organization
     *
     * @return School
     */
    public function getDefault($schoolId = null, UserProfile $userProfile, Organization $organization = null)
    {
        return $this->create()->filterByOrganization($organization)->findOne();
    }

    public function getSchoolList()
    {

    }
}
