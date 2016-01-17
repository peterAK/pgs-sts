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
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Repository\UserRepository;
use PGS\CoreDomainBundle\Repository\UserProfileRepository;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.user")
 */
class UserManager extends BaseUserManager
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserProfileRepository
     */
    protected $userProfileRepository;

    /**
     * @InjectParams({
     *      "securityContext"        = @Inject("security.context"),
     *      "userRepository"         = @Inject("pgs.core.repository.user"),
     *      "userProfileRepository"  = @Inject("pgs.core.repository.user_profile"),
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        UserRepository $userRepository,
        UserProfileRepository $userProfileRepository
    ) {
        $this->securityContext       = $securityContext;
        $this->userRepository        = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function findOneById($id)
    {
        return $this->userRepository->create()->findOneById($id);
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
            $user = $this->userRepository->findOneById($userProfile->getId());
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
     * @param string $userType
     *
     * @return array
     */
    public function convertTypeToRole($userType)
    {
        $roles[] = 'ROLE_USER';

        switch (strtolower($userType)) {
            case 'applicant':
                $roles[] = 'ROLE_APPLICANT';
                break;
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
}
