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

use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest;
use PGS\CoreDomainBundle\Repository\SchoolTestRepository;
use PGS\CoreDomainBundle\Security\AuthorizationStrategy;

class SchoolTestAuthorizationStrategy extends AuthorizationStrategy
{
    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria|\PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestQuery
     */
    function limit (\ModelCriteria $query)
    {
        if ($this->authorizer->isAdmin()) {
            if ($this->authorizer->school() instanceof School) {
                return $this->filterByParent($query);
            } else {
                return $query;
            }
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            return $this->filterByParent($query);
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
     * @return \PGS\CoreDomainBundle\Model\School\SchoolQuery
     */
    function filterByParent(\ModelCriteria $query)
    {
        /** @var SchoolTestRepository $query */
        return $query->filterBySchool($this->authorizer->school());
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByGrandParent(\ModelCriteria $query)
    {
        /** @var SchoolTestRepository $query */
        return $query
            ->useSchoolQuery()
            ->filterByOrganization($this->authorizer->organization())
            ->endUse();
    }

    /**
     * @param SchoolTest $object
     *
     * @return bool
     */
    function canDelete($object)
    {
        /** @var SchoolTest $object */
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
     * @param SchoolTest $object
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
