<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Container;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Manager\UserManager;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Repository\AcademicYearRepository;
use PGS\CoreDomainBundle\Repository\CountryRepository;
use PGS\CoreDomainBundle\Repository\OrganizationRepository;
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use PGS\CoreDomainBundle\Repository\SchoolYearRepository;
use PGS\CoreDomainBundle\Repository\StateRepository;
use PGS\CoreDomainBundle\Repository\StudentRepository;
use PGS\CoreDomainBundle\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.container.old_active_preference")
 */
class OldActivePreferenceContainer
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserManager
     *
     * @Inject("pgs.core.manager.user")
     */
    public $userManager;

    /**
     * @var AcademicYearQuery
     *
     * @Inject("pgs.core.query.academic_year")
     */
    public $academicYearQeury;

    /**
     * @var CountryRepository
     *
     * @Inject("pgs.core.repository.country")
     */
    public $countryRepository;

    /**
     * @var StateRepository
     *
     * @Inject("pgs.core.repository.state")
     */
    public $stateRepository;

    /**
     * @var OrganizationRepository
     *
     * @Inject("pgs.core.repository.organization")
     */
    public $organizationRepository;

    /**
     * @var SchoolRepository
     *
     * @Inject("pgs.core.repository.school")
     */
    public $schoolRepository;

    /**
     * @var SchoolYearRepository
     *
     * @Inject("pgs.core.repository.school_year")
     */
    public $schoolYearRepository;

    /**
     * @var UserProfileRepository
     *
     * @Inject("pgs.core.repository.user_profile")
     */
    public $userProfileRepository;

    /**
     * @var UserProfile;
     */
    private $userProfile;

    /**
     * @InjectParams({
     *      "session"         = @Inject("session"),
     *      "securityContext" = @Inject("security.context")
     * })
     */
    public function __construct(
        Session $session,
        SecurityContext $securityContext
    ) {
        $this->session = $session;
        $this->securityContext = $securityContext;
    }

    /**
     * @param int $userProfileId
     */
    public function setCurrentUserProfile($userProfileId)
    {
        $this->userProfile = $this->userManager->findOneProfileById($userProfileId);
    }

    /**
     * @return UserProfile
     */
    public function getCurrentUserProfile()
    {
        if (!$this->userProfile) {
            $this->userProfile = $this->userManager->getCurrentUserProfile();
        }

        return $this->userProfile;
    }

    /**
     * @return bool
     */
    public function getProfileCompleted()
    {
        return $this->getCurrentUserProfile()->getComplete();
    }

    /**
     * @param object      $manager
     * @param UserProfile $userProfile
     * @param string      $key
     * @param object|null $default
     *
     * @return null
     */
    private function getEntity($manager, UserProfile $userProfile, $key, $default = null)
    {
        $activePreferences = json_decode($userProfile->getActivePreferences(), true);
        if ($activePreferences === null) {
            $activePreferences = array();
        }

        if (array_key_exists($key . '_id', $activePreferences)) {
            $entity = $manager->findOneById($activePreferences[$key . '_id']);
            $this->setEntity($manager, $userProfile, $key, $entity);

            return $entity;
        }

        if ($default === null) {
            $defaultFunction = sprintf('getDefault%s', ucfirst($key));
            $default = $this->$defaultFunction($userProfile);
        }

        $this->setEntity($manager, $userProfile, $key, $default);

        return $default;
    }

    /**
     * @param object      $repository
     * @param UserProfile $userProfile
     * @param string      $key
     * @param object|null $entity
     */
    private function setEntity($repository, UserProfile $userProfile, $key, $entity)
    {
        if (!is_object($entity)) {
            if (!$entity = $repository->findOneById($entity)) {
                return;
            }
        }

        $userProfile->setActivePreference($key . '_id', $entity->getId());

        $this->userProfile->save();
        $this->session->set($key, $entity);
    }

    /**
     * @param UserProfile $userProfile
     * @param string      $key
     */
    private function unsetEntity(UserProfile $userProfile, $key)
    {
        $activePreferences = json_decode($userProfile->getActivePreferences(), true);

        if (array_key_exists($key . '_id', $activePreferences)) {
            $userProfile->unsetPreference($key . '_id');
            $this->userProfile->save();
            $this->session->remove($key);
        }
    }

    /**
     * Clear entity in Active Preference
     *
     * @param string $entity
     */
    public function unsetPreference($entity)
    {
        $userProfile  = $this->getCurrentUserProfile();

        $this->unsetEntity($userProfile, $entity);
    }

    /**
     * @return null|AcademicYear
     */
    public function getAcademicYearPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        // if academic_year in session variable exist, use it, else get it from profile

        return $this->getEntity($this->academicYearRepository, $userProfile, 'academicYear', null);
    }

    /**
     * @param int $academicYearId
     */
    public function setAcademicYear($academicYearId)
    {
        $userProfile  = $this->getCurrentUserProfile();
        $academicYear = $this->academicYearRepository->findOneById($academicYearId);

        $this->setEntity($this->academicYearRepository, $userProfile, 'academicYear', $academicYear);
    }

    /**
     * @return AcademicYear
     */
    protected function getDefaultAcademicYear()
    {
        return $this->academicYearRepository->setDefault();
    }

    /**
     * @return null|Organization
     */
    public function getOrganizationPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        if ( !$organization = $this->getEntity($this->organizationRepository, $userProfile, 'organization', null)) {
            return null;
        } elseif ($organization->isNew()) {
            return null;
        }

        return $organization;
    }

    /**
     * @param int $organizationId
     */
    public function setOrganizationPreference($organizationId)
    {
        $userProfile  = $this->getCurrentUserProfile();
        $organization = $this->organizationRepository->findOneById($organizationId);

        $this->setEntity($this->organizationRepository, $userProfile, 'organization', $organization);
        $this->unsetEntity($userProfile, 'school');
    }

    /**
     * @return Organization
     */
    protected function getDefaultOrganization()
    {
        $userProfile  = $this->getCurrentUserProfile();

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            return new Organization();
        } else {
            return $this->organizationRepository->getDefault(null, $userProfile);
        }
    }

    /**
     * Clear Organization in Active Preference
     */
    public function unsetOrganization()
    {
        $userProfile  = $this->getCurrentUserProfile();

        $this->unsetEntity($userProfile, 'organization');
    }

    /**
     * @return null|School
     */
    public function getSchoolPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        if ( !$school = $this->getEntity($this->schoolRepository, $userProfile, 'school', null) ) {
            return null;
        } elseif ($school->isNew()) {
            return null;
        }

        return $school;
    }

    /**
     * @param int $schoolId
     */
    public function setSchoolPreference($schoolId)
    {
        $userProfile  = $this->getCurrentUserProfile();
        $school       = $this->schoolRepository->findOneById($schoolId);

        $this->setEntity($this->schoolRepository, $userProfile, 'school', $school);
    }

    /**
     * @return School
     */
    public function getDefaultSchool()
    {
        $userProfile  = $this->getCurrentUserProfile();
        $organization = $this->getOrganizationPreference();

        if ( $organization instanceof Organization) {
            if ($this->securityContext->isGranted('ROLE_ADMIN')) {
                return new School();
            } else {
                return $this->schoolRepository->getDefault(null, $userProfile, $organization);
            }
        } else {
            return new School();
        }
    }

    /**
     * @return null|SchoolYear
     */
    public function getSchoolYearPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        return $this->getEntity($this->schoolYearRepository, $userProfile, 'schoolYear', null);
    }

    /**
     * @param int $schoolYearId
     */
    public function setSchoolYearPreference($schoolYearId)
    {
        $userProfile  = $this->getCurrentUserProfile();
        $schoolYear = $this->schoolYearRepository->findOneById($schoolYearId);

        $this->setEntity($this->schoolYearRepository, $userProfile, 'schoolYear', $schoolYear);
    }

    /**
     * @return SchoolYear
     */
    protected function getDefaultSchoolYear()
    {
        return $this->schoolYearRepository->setDefault(null, $this->getDefaultSchool());
    }

    public function getStudentPreference()
    {

    }
}
