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
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\om\BaseSchoolGradeLevelQuery;

/**
 * @Service("pgs.core.repository.school_grade_level")
 */
class SchoolGradeLevelRepository extends BaseSchoolGradeLevelQuery
{
    /**
     * @param int    $schoolGradeLevelId
     * @param SchoolGradeLevel $schoolGradeLevel
     *
     * @return SchoolGradeLevel
     */
    public function setDefault($schoolTestId = null, School $school)
    {
//        if (!$active = $this->create()->findOneByActive(true)) {
//            $this->create()->update(['Active' => false]);
//        }
//
//        if ($academicTestId === null) {
//            if (!$activeTest = $this->create()->findOneByActive(true)) {
//                $activeTest = $this->create()->orderByTestEnd(\Criteria::DESC)->findOne();
//            }
//        } else {
//            $activeTest = $this->create()->findOneById($academicTestId);
//        }
//
//        $activeTest->setActive(true)->save();

//        return $activeTest;
    }

    /**
     * @param int $schoolTestId
     */
    public function makeActive($schoolGradeLevelId)
    {
        $schoolGradeLevel = $this->create()->findOneBySchoolId($schoolGradeLevelId);

        $this->create()
            ->filterBySchool($schoolGradeLevel->getSchool())
            ->update(['Active' => false]);

        $schoolGradeLevel->setActive(true);
        $schoolGradeLevel->save();
    }
}
