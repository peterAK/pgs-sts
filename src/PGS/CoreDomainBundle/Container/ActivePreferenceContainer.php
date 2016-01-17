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

namespace PGS\CoreDomainBundle\Container;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Manager\OrganizationManager;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Manager\SchoolYearManager;
use PGS\CoreDomainBundle\Manager\ApplicationManager;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;

/**
 * @Service("pgs.core.container.active_preference")
 */
class ActivePreferenceContainer extends AbstractPreferenceContainer
{
    /** @var OrganizationManager */
    protected $organizationManager;

    /** @var SchoolManager */
    protected $schoolManager;

    /** @var SchoolYearManager */
    protected $schoolYearManager;

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
        if (!$userProfile->isNew()) {
            $activePreferences = json_decode($userProfile->getActivePreferences(), true);

            if ($activePreferences === null) {
                $activePreferences = array();
            }

            if (array_key_exists($key . '_id', $activePreferences)) {
                if ($activePreferences[$key . '_id'] !== null ) {
                    $entity = $manager->findOne($activePreferences[$key . '_id']);

                    if ($entity !== null) {
                        $this->setEntity($manager, $userProfile, $key, $entity);

                        return $entity;
                    }
                }
            }

            if ($default === null) {
                $defaultFunction = sprintf('getDefault%s', ucfirst($key));
                $default = $this->$defaultFunction($userProfile);
            }
            $this->setEntity($manager, $userProfile, $key, $default);

            return $default;
        }

        return $this->getPreferenceCookie($key);
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
            if (!$entity = $repository->findOne($entity)) {
                return;
            }
        }

        if (!$this->getCurrentUserProfile()->isNew()) {
            $userProfile->setActivePreference($key . '_id', $entity->getId());
            $this->userProfile->save();
        }

        $this->setPreferenceCookie($key, $entity);
    }

    /**
     * @param UserProfile $userProfile
     * @param string      $key
     */
    private function unsetEntity(UserProfile $userProfile, $key)
    {
        if (!$userProfile->isNew()) {
            $activePreferences = json_decode($userProfile->getActivePreferences(), true);

            if (array_key_exists($key . '_id', $activePreferences)) {
                $userProfile->unsetPreference($key . '_id');
                $this->userProfile->save();
            }
        }

        $this->unsetPreferenceCookie($key);
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
     * @param $key
     * @param $entity
     */
    private function setPreferenceCookie($key, $entity)
    {
        $this->session->set($key, $entity);
    }

    /**
     * @param $key
     */
    private function unsetPreferenceCookie($key)
    {
        $this->session->remove($key);
    }

    private function getPreferenceCookie($key)
    {
        return $this->session->get($key);
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
     * @param int $userProfileId
     */
    public function setCurrentUserProfile($userProfileId)
    {
        $this->userProfile = $this->userManager->findOneProfileById($userProfileId);
    }

    /**
     * @return bool
     */
    public function getProfileCompleted()
    {
        return $this->getCurrentUserProfile()->getComplete();
    }

    /**
     * @return int
     */
    public function getMyOrganizationId()
    {
        return $this->getMyOrganization()->getId();
    }

    public function getMyOrganization()
    {
        if ( $this->isAdmin() || $this->isSchoolAdmin() || $this->isPrincipal() ||
            $this->isCounselor() || $this->isTeacher() || $this->isStudent() ) {
            return $this->getCurrentUserProfile()->getOrganization();
        }

        return new Organization;
    }

    /**
     * @return null|Organization
     */
    public function getOrganizationPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        /** @var Organization $organization */
        if ( !$organization = $this->getEntity($this->getOrganizationManager(), $userProfile, 'organization', null)) {
            return null;
        }

        if ($organization->isNew()) {
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

        $manager = $this->getOrganizationManager();

        if ($organization = $manager->findOneById($organizationId)) {
            $this->setEntity($manager, $userProfile, 'organization', $organization);
            $this->unsetEntity($userProfile, 'school');
            $this->unsetEntity($userProfile, 'schoolYear');
        }
    }

    /**
     * @return Organization
     */
    protected function getDefaultOrganization()
    {
        $userProfile  = $this->getCurrentUserProfile();
        $organization = $this->getOrganizationManager()->getDefault();

        if ($organization === null) {
            $organization = new Organization();
        }

        return $organization;
    }

    /**
     * @return OrganizationManager
     */
    protected function getOrganizationManager()
    {
        if (!$this->organizationManager instanceof OrganizationManager)
        {
            $this->organizationManager = $this->container->get('pgs.core.manager.organization');
        }

        return $this->organizationManager;
    }

    /**
     * @return null|School
     */
    public function getSchoolPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        /** @var School $school */
        if ( !$school = $this->getEntity($this->getSchoolManager(), $userProfile, 'school', null)) {
            return null;
        }

        if ($school->isNew()) {
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
        $manager      = $this->getSchoolManager();

        if ($school = $manager->findOne($schoolId)) {
            $this->setEntity($manager, $userProfile, 'school', $school);
            $this->unsetEntity($userProfile, 'schoolYear');
        }
    }

    /**
     * @return School
     */
    protected function getDefaultSchool()
    {
        $organization = $this->getOrganizationPreference();
        $school       = $this->getSchoolManager()->findOneByOrganization($organization);

        if($organization instanceof Organization){
            if ($school === null) {
                $school = new School();
            }
            return $school;
        }
        return $school;
    }

    /**
     * @return SchoolManager
     */
    protected function getSchoolManager()
    {
        if (!$this->schoolManager instanceof SchoolManager)
        {
            $this->schoolManager = $this->container->get('pgs.core.manager.school');
        }

        return $this->schoolManager;
    }

    /**
     * @return null|SchoolYear
     */
    public function getSchoolYearPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        /** @var SchoolYear $schoolYear */
        if ( !$schoolYear = $this->getEntity($this->getSchoolYearManager(), $userProfile, 'schoolYear', null)) {
            return null;
        }

        if ($schoolYear->isNew()) {
            return null;
        }

        return $schoolYear;
    }

    /**
     * @return SchoolYear
     */
    protected function getDefaultSchoolYear()
    {
        $school       = $this->getSchoolPreference();

        if ($school && !$school->isNew()) {
            return $this->getSchoolYearManager()->findLatestActiveBySchool($school);
        } else {
            return null;
        }
    }

    /**
     * @return SchoolYearManager
     */
    protected function getSchoolYearManager()
    {
        if (!$this->schoolYearManager instanceof SchoolYearManager)
        {
            $this->schoolYearManager = $this->container->get('pgs.core.manager.school_year');
        }

        return $this->schoolYearManager;
    }

    public function getAcademicYearPreference()
    {
        return true;
    }
}
