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
use PGS\CoreDomainBundle\Manager\PrincipalManager;
use PGS\CoreDomainBundle\Model\UserProfile;
use PGS\CoreDomainBundle\Model\Principal\Principal;

/**
 * @Service("pgs.core.container.active_preference")
 */
class ActivePreferenceContainer extends AbstractPreferenceContainer
{
    /** @var PrincipalManager */
    protected $principalManager;

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
     * @return PrincipalManager
     */
    protected function getPrincipalManager()
    {
        if (!$this->principalManager instanceof PrincipalManager)
        {
            $this->principalManager = $this->container->get('pgs.core.manager.principal');
        }

        return $this->principalManager;
    }

    /**
     * @return int
     */
    public function getMyPrincipalId()
    {
        return $this->getMyPrincipal()->getId();
    }

    /**
     * @return Principal
     */
    public function getMyPrincipal()
    {
        if ( $this->isAdmin() || $this->isOffice() || $this->isPrincipal() || $this->isSales() ) {
            return $this->getCurrentUserProfile()->getPrincipal();
        }

        return new Principal;
    }

    /**
     * @return null|Principal
     */
    public function getPrincipalPreference()
    {
        $userProfile = $this->getCurrentUserProfile();

        /** @var Principal $principal*/
        if ( !$principal = $this->getEntity($this->getPrincipalManager(), $userProfile, 'principal', null)) {
            return null;
        }

        if ($principal->isNew()) {
            return null;
        }

        return $principal;
    }

    /**
     * @param int $principalId
     */
    public function setPrincipalPreference($principalId)
    {
        $userProfile  = $this->getCurrentUserProfile();

        $manager = $this->getPrincipalManager();

        if ($principal = $manager->findOneById($principalId)) {
            $this->setEntity($manager, $userProfile, 'principal', $principal);
        }
    }

    /**
     * @return Principal
     */
    protected function getDefaultPrincipal()
    {
        $userProfile  = $this->getCurrentUserProfile();
        $principal = $this->getPrincipalManager()->getDefault();

        if ($principal === null) {
            $principal = new Principal();
        }

        return $principal;
    }
}
