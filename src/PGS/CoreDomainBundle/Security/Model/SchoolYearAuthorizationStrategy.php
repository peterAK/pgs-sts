<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Security\Model;

use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Repository\SchoolYearRepository;
use PGS\CoreDomainBundle\Security\AuthorizationStrategy;

class SchoolYearAuthorizationStrategy extends AuthorizationStrategy
{
    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria|\PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery
     */
    function limit(\ModelCriteria $query)
    {
        /** @var SchoolYearRepository $query */
        if ($this->authorizer->isAdmin() || $this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($this->authorizer->school() instanceof School) {
                return $this->filterByParent($query);
            } else {
                if ($this->authorizer->organization() instanceof Organization) {
                    return $this->filterByGrandParent($query);
                }
                return $query;
            }
        }

        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterBySelf(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery
     */
    function filterByParent(\ModelCriteria $query)
    {
        /** @var SchoolYearRepository $query */
        return $query->filterBySchool($this->authorizer->school());
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByGrandParent(\ModelCriteria $query)
    {
        /** @var SchoolYearRepository $query */
        return $query
            ->useSchoolQuery()
                ->filterByOrganization($this->authorizer->organization())
            ->endUse();
    }

    /**
     * @param SchoolYear $object
     *
     * @return bool
     */
    function canDelete($object)
    {
        /** @var SchoolYear $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getSchool()->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param SchoolYear $object
     *
     * @return bool
     */
    function canEdit($object)
    {
        return $this->canDelete($object);
    }

    /**
     * @param $object
     *
     * @return bool
     */
    function canView($object)
    {
        return true;
    }
}
