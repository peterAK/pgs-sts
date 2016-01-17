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

use FOS\UserBundle\Propel\UserManager As BaseUserManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserQuery;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\UserProfileQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.user_profile")
 */
class UserProfileManager extends Authorizer
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var UserQuery
     */
    protected $userQuery;

    /**
     * @var UserProfileQuery
     */
    protected $userProfileQuery;

    /**
     * @InjectParams({
     *      "securityContext"        = @Inject("security.context"),
     *      "userQuery"              = @Inject("pgs.core.query.user"),
     *      "userProfileQuery"       = @Inject("pgs.core.query.user_profile"),
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        UserQuery $userQuery,
        UserProfileQuery $userProfileQuery
    ) {
        $this->securityContext       = $securityContext;
        $this->userQuery        = $userQuery;
        $this->userProfileQuery = $userProfileQuery;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function findOneById($id)
    {
        return $this->userProfileQuery->create()->findOneById($id);
    }

    /**
     * Get the UserProfile from the current security user.
     * Reload UserProfile from the db when $reload = true
     *
     * @param bool $reload
     *
     * @return null|UserProfile
     */
    public function getCurrentUserProfile($reload = false)
    {
        $user = $this->securityContext->getToken()->getUser();
        if( $user instanceof User) {
            // check if there is no profile record for the user
            if (!$userProfile = $user->getUserProfile()) {
                $userProfile = new UserProfile();
                $userProfile->setUser($user);
                $userProfile->setFirstName($user->getUsername());
                $userProfile->setCountryId(Country::UNKNOWN);
                $userProfile->save();
            }
        } else {
            return null;
        }

        if ($reload) {
            $user = $this->userQuery->findOneById($userProfile->getId());
            $userProfile = $user->getUserProfile();
        }

        return $userProfile;
    }

    /**
     * @param int $id
     *
     * @return UserProfile
     */
    public function findOneProfileById($id)
    {
        return $this->findOneById($id)->getUserProfile();
    }

    /**
     * @param int $id
     *
     * @return UserProfile
     */
    public function findByOrganization($id)
    {
        return $this
            ->userProfileRepository
            ->filterByOrganizationId($id)
            ->find();
    }

    /**
     *
     * @return UserProfile
     */
    public function findAll()
    {
        return $this
            ->userProfileRepository
            ->find();
    }

    /**
     * @param string $userType
     *
     * @return array
     */
    public function convertTypeToRole($userType)
    {
        $roles[] = 'ROLE_USER';

        switch (strtolower($userType)) {
            case 'student':
                $roles[] = 'ROLE_STUDENT';
                break;
            case 'parent':
                $roles[] = 'ROLE_PARENT';
                break;
            case 'teacher':
                $roles[] = 'ROLE_TEACHER';
                break;
            case 'principal':
                $roles[] = 'ROLE_PRINCIPAL';
                break;
            case 'school':
                $roles[] = 'ROLE_SCHOOL';
                break;
            case 'admin':
                $roles[] = 'ROLE_ADMIN';
                break;
        }

        return $roles;
    }

    /**
     * @param mixed $modelIdentifier
     * @param int $status
     */
    public function limitList($modelIdentifier)
    {
        // TODO: Implement limitList() method.
    }

    public function canAdd()
    {
        // TODO: Implement canAdd() method.
    }

    /** @param mixed $modelIdentifier */
    public function canEdit($modelIdentifier)
    {
        // TODO: Implement canEdit() method.
    }

    /** @param mixed $modelIdentifier */
    public function canDelete($modelIdentifier)
    {
        // TODO: Implement canDelete() method.
    }

    /** @param mixed $modelIdentifier */
    public function canView($modelIdentifier)
    {
        // TODO: Implement canView() method.
    }

}
