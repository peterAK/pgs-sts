<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 1/20/2014.
 */

namespace PGS\CoreDomainBundle\Model\School;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\School\om\BaseSchoolQuery;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;

/**
 * @Service("pgs.core.query.school")
 */
class SchoolQuery extends BaseSchoolQuery
{

    /**
     * @param $school
     *
     * @return SchoolQuery
     */
    public function getNameBySchoolId(School $school)
    {
        return $this->create()->filterBySchool($school);
    }
}