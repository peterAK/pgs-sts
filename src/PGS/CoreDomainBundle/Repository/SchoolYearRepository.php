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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\SchoolYear\om\BaseSchoolYearQuery;

/**
 * @Service("pgs.core.repository.school_year")
 */
class SchoolYearRepository extends BaseSchoolYearQuery
{
    /**
     * @param int    $schoolYearId
     * @param School $school
     *
     * @return SchoolYear
     */
    public function setDefault($schoolYearId = null, School $school)
    {
//        if (!$active = $this->create()->findOneByActive(true)) {
//            $this->create()->update(['Active' => false]);
//        }
//
//        if ($academicYearId === null) {
//            if (!$activeYear = $this->create()->findOneByActive(true)) {
//                $activeYear = $this->create()->orderByYearEnd(\Criteria::DESC)->findOne();
//            }
//        } else {
//            $activeYear = $this->create()->findOneById($academicYearId);
//        }
//
//        $activeYear->setActive(true)->save();

//        return $activeYear;
    }

    /**
     * @param int $schoolYearId
     */
    public function makeActive($schoolYearId)
    {
        $schoolYear = $this->create()->findOneById($schoolYearId);

        $this->create()
            ->filterBySchool($schoolYear->getSchool())
            ->update(['Active' => false]);

        $schoolYear->setActive(true);
        $schoolYear->save();
    }
}
