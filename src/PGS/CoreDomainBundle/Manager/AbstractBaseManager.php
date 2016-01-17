<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use PGS\CoreDomainBundle\Container\OldActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class AbstractBaseManager
{
    /**
     * @var SecurityContextInterface
     *
     * @Inject("security.context")
     */
    public $securityContext;

    /**
     * @var SessionInterface
     *
     * @Inject("session")
     */
    public $session;

    /**
     * @var OldActivePreferenceContainer
     *
     * @Inject("pgs.core.container.old_active_preference")
     */
    public $activePreference;

    /**
     * @return bool
     */
    protected function isAdmin()
    {
        return $this->securityContext->isGranted('ROLE_ADMIN');
    }

    /**
     * @return bool
     */
    protected function isPrincipal()
    {
        return $this->securityContext->isGranted('ROLE_PRINCIPAL');
    }

    /**
     * @return bool
     */
    protected function isSchoolAdmin()
    {
        return $this->securityContext->isGranted('ROLE_SCHOOL');
    }

    /**
     * @return bool
     */
    protected function isTeacher()
    {
        return $this->securityContext->isGranted('ROLE_TEACHER');
    }

    /**
     * @return bool
     */
    protected function isParent()
    {
        return $this->securityContext->isGranted('ROLE_PARENT');
    }

    /**
     * @return bool
     */
    protected function isStudent()
    {
        return $this->securityContext->isGranted('ROLE_STUDENT');
    }

    /**
     * @param object $query
     *
     * @return object $query
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function restrictOrganization($query)
    {
        $organization = $this->activePreference->getOrganizationPreference();

        if ($this->isAdmin()) {
            if (!$organization instanceof Organization) {
                return $query;
            }
        }

        return $query->filterById($organization->getId());
    }

    /**
     * @param object $query
     *
     * @return object $query
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function restrictSchool($query)
    {
        $school = $this->activePreference->getSchoolPreference();

        if (!$school instanceof School) {
            if ($this->isAdmin() || $this->isPrincipal() || $this->isSchoolAdmin()) {
                if (!$organization = $this->activePreference->getOrganizationPreference()) {
                    if ($this->isAdmin()) {
                        return $query;
                    }
                    throw new AccessDeniedException('No `organization` preference');
                }

                if ($query instanceof SchoolRepository) {
                    return $query
                        ->filterByOrganization($organization);
                } else {
                    return $query
                        ->useSchoolQuery()
                        ->filterByOrganization($organization)
                        ->endUse();
                }
            }

            throw new AccessDeniedException('No `school` preference');
        }

        return $query->filterById($school->getId());
    }
}
