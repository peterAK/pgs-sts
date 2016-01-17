<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 12/1/2014.
 */

namespace PGS\CoreDomainBundle\Container;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Manager\UserManager;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractPreferenceContainer
{
    /**
     * @var Session
     */
    protected $session;

    /** @var ContainerInterface */
    protected $container;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var UserManager
     *
     * @Inject("pgs.core.manager.user")
     */
    public $userManager;

    /**
     * @var UserProfileQuery
     *
     * @Inject("pgs.core.query.user_profile")
     */
    public $userProfileQuery;

    /**
     * @var UserProfile;
     */
    public $userProfile;

    /**
     * @InjectParams({
     *      "session"         = @Inject("session"),
     *      "container"       = @Inject("service_container"),
     *      "securityContext" = @Inject("security.context")
     * })
     */
    public function __construct(
        Session $session,
        ContainerInterface $container,
        SecurityContext $securityContext
    ) {
        $this->session         = $session;
        $this->container       = $container;
        $this->securityContext = $securityContext;
    }

    /**
     * @return SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
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
    public function isSchoolAdmin()
    {
        return $this->securityContext->isGranted('ROLE_SCHOOL');
    }

    /**
     * @return bool
     */
    public function isPrincipal()
    {
        return $this->securityContext->isGranted('ROLE_PRINCIPAL');
    }

    /**
     * @return bool
     */
    public function isCounselor()
    {
        return $this->securityContext->isGranted('ROLE_COUNSELOR');
    }

    /**
     * @return bool
     */
    public function isTeacher()
    {
        return $this->securityContext->isGranted('ROLE_TEACHER');
    }

    /**
     * @return bool
     */
    public function isParent()
    {
        return $this->securityContext->isGranted('ROLE_PARENT');
    }

    /**
     * @return bool
     */
    public function isStudent()
    {
        return $this->securityContext->isGranted('ROLE_STUDENT');
    }

    /**
     * @return bool
     */
    public function isApplicant()
    {
        return $this->securityContext->isGranted('ROLE_APPLICANT');
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return !$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * @return bool
     */
    public function isSingleScope()
    {
        return ($this->getContainer()->getParameter('site_scope') == 'single');
    }

    /**
     * @return bool
     */
    public function isMultiScope()
    {
        return ($this->getContainer()->getParameter('site_scope') !== 'single');
    }

    /**
     * @return mixed|null
     */
    public function getSingleSiteOrganizationId()
    {
        if ($this->isSingleScope())
        {
            return $this->getContainer()->getParameter('site_organization');
        }

        return null;
    }

    /**
     * @return int|mixed
     */
    public function getMultiSiteOrganizationId()
    {
        if ($this->isMultiScope())
        {
            return $this->getContainer()->getParameter('site_organization');
        }

        return 0;
    }
}
