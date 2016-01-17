<?php

/*
* This file is part of the PGS/CoreDomainBundle package.
*
* (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace PGS\CoreDomainBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use PGS\CoreDomainBundle\Model\UserPeer;

/**
 * @Service("pgs.core.extension.user_status")
 * @Tag("twig.extension")
 */
class UserStatusExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('user_status', array($this, 'userStatusFilter')),
        );
    }

    /**
     * @param int $status
     *
     * @return null|string
     */
    public function userStatusFilter($status) {
        switch($status) {
            case UserPeer::STATUS_NEW:
                return 'New';
            case UserPeer::STATUS_PENDING:
                return 'Pending';
            case UserPeer::STATUS_ACTIVE:
                return 'Active';
            case UserPeer::STATUS_INACTIVE:
                return 'Inactive';
            case UserPeer::STATUS_BANNED:
                return 'Banned';
        }
    }

    public function getName()
    {
        return 'user_status_extension';
    }
}
