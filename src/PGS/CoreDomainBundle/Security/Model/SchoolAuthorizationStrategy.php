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
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use PGS\CoreDomainBundle\Security\AuthorizationStrategy;

class SchoolAuthorizationStrategy extends AuthorizationStrategy
{
    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria|\PGS\CoreDomainBundle\Model\School\SchoolQuery
     */
    function limit(\ModelCriteria $query)
    {
        if ($this->authorizer->isAdmin()) {
            if ($this->authorizer->organization() instanceof Organization) {
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
        /** @var SchoolRepository $query */
        return $query->filterByOrganization($this->authorizer->organization());
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByGrandParent(\ModelCriteria $query)
    {
        return $this->filterByUser($query);
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByUser(\ModelCriteria $query)
    {
        /** @var SchoolRepository $query */
        return $query
            ->useOrganizationQuery()
                ->filterByUser($this->authorizer->user())
            ->endUse();
    }

    /**
     * @param School $object
     *
     * @return bool
     */
    function canDelete($object )
    {
        /** @var School $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param School $object
     *
     * @return bool
     */
    function canEdit($object)
    {
        /**@var School $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        return false;
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
