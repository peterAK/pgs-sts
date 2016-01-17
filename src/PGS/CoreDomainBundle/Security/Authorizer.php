<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 11/27/2014.
 */

namespace PGS\CoreDomainBundle\Security;

use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\User;

abstract class Authorizer implements AuthorizerInterface
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @param ActivePreferenceContainer $activePreference
     */
    public function __construct(
        ActivePreferenceContainer $activePreference
    ) {
        $this->activePreference = $activePreference;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->activePreference->isAdmin();
    }

    /**
     * @return bool
     */
    public function isSchoolAdmin()
    {
        return $this->activePreference->isSchoolAdmin();
    }

    /**
     * @return bool
     */
    public function isPrincipal()
    {
        return $this->activePreference->isPrincipal();
    }

    /**
     * @return bool
     */
    public function isCounselor()
    {
        return $this->activePreference->isCounselor();
    }

    /**
     * @return bool
     */
    public function isTeacher()
    {
        return $this->activePreference->isTeacher();
    }

    /**
     * @return bool
     */
    public function isParent()
    {
        return $this->activePreference->isParent();
    }

    /**
     * @return bool
     */
    public function isStudent()
    {
        return $this->activePreference->isStudent();
    }

    /**
     * @return bool
     */
    public function isApplicant()
    {
        return $this->activePreference->isApplicant();
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->activePreference->isLoggedIn();
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return $this->activePreference->isGuest();
    }

    /**
     * @return bool
     */
    public function isMemberOfOrganization()
    {
        if ($this->isSchoolAdmin() || $this->isPrincipal() || $this->isCounselor() || $this->isTeacher() || $this->isStudent()) {
            return true;
        }

        return false;
    }

    /**
     * @return User|null
     */
    public function getCurrentUser()
    {
        return $this->activePreference->getSecurityContext()->getToken()->getUser();
    }

    /**
     * @return bool
     */
    public function isSingleScope()
    {
        return $this->activePreference->isSingleScope();
    }

    /**
     * @return bool
     */
    public function isMultiScope()
    {
        return $this->activePreference->isMultiScope();
    }

    /**
     * @return mixed|null
     */
    public function getSingleSiteOrganizationId()
    {
        return $this->activePreference->getSingleSiteOrganizationId();
    }

    /**
     * @return int|mixed
     */
    public function getMultiSiteOrganizationId()
    {
        return $this->activePreference->getMultiSiteOrganizationId();
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\Organization\Organization
     */
    public function getMyOrganization()
    {
        return $this->activePreference->getMyOrganization();
    }

    /**
     * @return int
     */
    public function getMyOrganizationId()
    {
        return $this->activePreference->getMyOrganizationId();
    }

    /**
     * @param mixed $roles
     *
     * @return array
     */
    public function getUserRoles($roles = null)
    {
        $roleArray = array();

        if ($roles === null)
        {
            $roles = $this->activePreference->getSecurityContext()->getToken()->getRoles();
            foreach($roles as $role)
            {
                $roleArray[] = $role->getRole();
            }
            $roles = $roleArray;
        }

        return array_unique($roles);
    }

    /**
     * @param mixed $roles
     *
     * @return array
     */
    public function validRole($roles)
    {
        $userRoles = $this->getUserRoles();

        return array_intersect($roles, $userRoles);
    }
}
