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

namespace PGS\CoreDomainBundle\Model\SchoolYear;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\SchoolYear\om\BaseSchoolYearQuery;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\Course\Course;

/**
 * @Service("pgs.core.query.school_year")
 */
class SchoolYearQuery extends BaseSchoolYearQuery
{
    /**
     * @return SchoolYearQuery
     */
    public function getNoSchoolYearChoice()
    {
        return $this->create()->filterById();
    }

    /**
     * @param School $school
     *
     * @return SchoolYearQuery
     */
    public function getSchoolYearBySchoolChoices(School $school)
    {
        return $this->create()->filterBySchool($school);
    }

    /**
     * @param Course $course
     *
     * @return SchoolYearQuery
     */
    public function getSchoolYearByCourse(Course $course)
    {
        return $this->create()->filterBySchool($course->getSchoolId());
    }

}
