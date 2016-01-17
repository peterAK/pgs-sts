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
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\om\BaseLevelQuery;

/**
 * @Service("pgs.core.repository.level")
 */
class LevelRepository extends BaseLevelQuery
{
    /**
     * @return \PGS\CoreDomainBundle\Model\Level\LevelQuery
     */
    public function getLevelChoices()
    {
        return $this->create()->orderBySortableRank();
    }
}