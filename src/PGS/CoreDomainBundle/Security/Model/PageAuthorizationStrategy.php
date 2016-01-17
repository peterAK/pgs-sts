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

use PGS\CoreDomainBundle\Model\Page;
use PGS\CoreDomainBundle\Repository\PageRepository;
use PGS\CoreDomainBundle\Security\AuthorizationStrategy;

class PageAuthorizationStrategy extends AuthorizationStrategy
{
    function limit(\ModelCriteria $query)
    {
        if ($this->authorizer->isAdmin()) {
            return $query;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            return $this->filterByGrandParent($query);
        }

        return $query;
    }

    function filterBySelf(\ModelCriteria $query)
    {
        return $query;
    }

    function filterByParent(\ModelCriteria $query)
    {
        /** @var PageRepository $query */
        return $query->filterBySchool($this->authorizer->school());
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByGrandParent(\ModelCriteria $query)
    {
        /** @var PageRepository $query */
        return $query
            ->useSchoolQuery()
                ->filterByOrganization($this->authorizer->organization())
            ->endUse();
    }

    function filterByUser(\ModelCriteria $query)
    {
        /** @var PageRepository $query */
        return $query->filterByUser($this->authorizer->user());
    }

    function canDelete($object )
    {
        /**@var Page $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getSchool()->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        if ($this->authorizer->isTeacher() || $this->authorizer->isParent() || $this->authorizer->isStudent()) {
            if ($object->getSchool() == $this->authorizer->school()) {
                return true;
            }
        }

        return false;
    }

    function canEdit($object)
    {
        /**@var Page $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getSchool()->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        if ($this->authorizer->isTeacher() || $this->authorizer->isParent() || $this->authorizer->isStudent()) {
            if ($object->getUser() == $this->authorizer->user()) {
                return true;
            }
        }

        return false;
    }

    function canView($object)
    {
        /**@var Page $object */
        if ($this->authorizer->isAdmin()) {
            return true;
        }

        if ($this->authorizer->isPrincipal() || $this->authorizer->isSchoolAdmin()) {
            if ($object->getSchool()->getOrganization() == $this->authorizer->organization()) {
                return true;
            }
        }

        if ($this->authorizer->isTeacher() || $this->authorizer->isParent() || $this->authorizer->isStudent()) {
            if ($object->getSchool() == $this->authorizer->school()) {
                return true;
            }
        }

        return false;
    }
}
