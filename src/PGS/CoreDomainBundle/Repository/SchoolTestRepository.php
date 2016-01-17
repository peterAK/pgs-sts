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
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest;
use PGS\CoreDomainBundle\Model\SchoolTest\om\BaseSchoolTestQuery;

/**
 * @Service("pgs.core.repository.school_test")
 */
class SchoolTestRepository extends BaseSchoolTestQuery
{
    /**
     * @param int    $schoolTestId
     * @param School $school
     *
     * @return SchoolTest
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
}
