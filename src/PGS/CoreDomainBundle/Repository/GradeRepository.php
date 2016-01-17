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
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Grade\om\BaseGradeQuery;

/**
 * @Service("pgs.core.repository.grade")
 */
class GradeRepository extends BaseGradeQuery
{
    /**
     * @return \PGS\CoreDomainBundle\Model\Grade\GradeQuery
     */
    public function getGradeChoices()
    {
        return $this->create()->orderBySortableRank();
    }
}
