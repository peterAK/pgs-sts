<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Repository;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use PGS\CoreDomainBundle\Model\Icon\om\BaseIconQuery;
use PGS\CoreDomainBundle\Model\UserProfile;

/**
 * @Service("pgs.core.repository.icon")
 */
class IconRepository extends BaseIconQuery
{
    /**
     * @param int         $iconId
     * @param UserProfile $userProfile
     *
     * @return Icon
     */
    public function getDefault($iconId = null, UserProfile $userProfile)
    {
        if (!$icon = $this->create()->findOneByUserId($userProfile->getId())) {
            $icon = new Icon();
        }

        return $icon;
    }
}
