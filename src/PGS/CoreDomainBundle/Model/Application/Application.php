<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Model\Application;

use PGS\CoreDomainBundle\Model\Application\om\BaseApplication;

class Application extends BaseApplication
{
    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->getLastName() === null || trim($this->getLastName()) == '' ) {
            return $this->getFirstName();
        } else {
            return $this->getLastName().', '.$this->getFirstName();
        }
    }

    public static function getStatuses()
    {
        $statuses = ApplicationPeer::getValueSets();

        return $statuses['application.status'];
    }

    public static function getGenders()
    {
        $genders = ApplicationPeer::getValueSets();

        return $genders['application.gender'];
    }

    public static function getChildStatuses()
    {
        $childStatuses = ApplicationPeer::getValueSets();

        return $childStatuses['application.child_status'];
    }
}
