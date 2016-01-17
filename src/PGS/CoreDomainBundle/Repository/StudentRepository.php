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
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\om\BaseStudentQuery;
use PGS\CoreDomainBundle\Model\UserProfile;

/**
 * @Service("pgs.core.repository.student")
 */
class StudentRepository extends BaseStudentQuery
{
    /**
     * @param int         $studentId
     * @param UserProfile $userProfile
     * @param School|null $school
     *
     * @return Student
     */
    public function getDefault($studentId = null, UserProfile $userProfile, School $school = null)
    {
//        if ($organization === null)
//        {
//
//        }
//
//        if (!$school = $this->create()->findOneByUserId($userProfile->getId())) {
            $student = $this->create()->findOneById(1);
//        }

        return $student;
    }
}
