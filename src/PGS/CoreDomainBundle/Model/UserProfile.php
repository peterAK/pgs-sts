<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model;

use PGS\CoreDomainBundle\Model\om\BaseUserProfile;

class UserProfile extends BaseUserProfile
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstName();
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function getActivePreference($key)
    {
        $activePreferences = json_decode($this->getActivePreferences());

        return isset($activePreferences[$key]) ? $activePreferences[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return static
     */
    public function setActivePreference($key, $value)
    {
        $activePreferences = json_decode($this->getActivePreferences(), true);
        $activePreferences[$key] = $value;

        $this->setActivePreferences(json_encode($activePreferences));

        return $this;
    }

    /**
     * @param $key
     *
     * @return static
     */
    public function unsetPreference($key)
    {
        $activePreferences = json_decode($this->getActivePreferences(), true);
        unset($activePreferences[$key]);

        $this->setActivePreferences(json_encode($activePreferences));

        return $this;
    }
}
