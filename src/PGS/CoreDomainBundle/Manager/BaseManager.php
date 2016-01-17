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
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\OldActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

class BaseManager extends ContainerAware
{
    /**
     * @var SecurityContextInterface
     * @Inject("security.context")
     */
    public $securityContext;

    /**
     * @var SessionInterface
     * @Inject("session")
     */
    public $session;

    /**
     * @var OldActivePreferenceContainer
     * @Inject("pgs.core.container.old_active_preference")
     */
    public $activePreference;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
    }

    /**
     * @return bool
     */
    public function isAdmin()
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

    protected function isGuest()
    {
        return !$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }
}
