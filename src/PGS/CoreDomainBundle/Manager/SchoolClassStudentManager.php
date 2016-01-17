<?php

/**
* This file is part of the PGS/CoreDomainBundle package.
*
* (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudentQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_class_student")
 */
class SchoolClassStudentManager extends Authorizer
{
    /**
     * @var SchoolClassStudentQuery
     */
    private $schoolClassStudentQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "schoolClassStudentQuery" = @Inject("pgs.core.query.school_class_student")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolClassStudentQuery $schoolClassStudentQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->schoolClassStudentQuery = $schoolClassStudentQuery;
    }

    /**
     * @return SchoolClassStudentQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolClassStudentQuery->create();
    }

    /**
     * @return schoolClassStudent
     */
    public function getDefault()
    {
        if(!$schoolClassStudent = $this->getCurrentUser()->getUserProfile()->getSchoolClassStudentId()){
            $schoolClassStudent = new SchoolClassStudent();
        }

        return $schoolClassStudent;
    }

    /**
     * @param mixed $schoolClassStudent
     *
     * @return SchoolClassStudent
     */
    public function findOne($schoolClassStudent)
    {
        if ($schoolClassStudent instanceof SchoolClassStudent) {
            $schoolClassStudent = $schoolClassStudent->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolClassStudent);
    }

    /**
     * @param $schoolClassId
     * @param $studentId
     * @return SchoolClassStudent
     */
    public function findOneBySchoolClass($schoolClassId, $studentId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolClassId($schoolClassId)
            ->filterByStudentId($studentId)
            ->findOne();
    }

    /**
     * @param $studentId
     * @return SchoolClassStudent
     */
    public function findOneByStudent( $studentId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterByStudentId($studentId)
            ->findOne();
    }

//    /**
//     * @param $schoolClassId
//     * @param $courseId
//     * @return SchoolClassStudent
//     */
//    public function findOneBySchoolClassCourse($schoolClassId, $courseId)
//    {
//        return $this->limitList($this->getBaseQuery())
//            ->filterBySchoolClassId($schoolClassId)
//            ->filterBysc
//            ->findOne();
//    }

    /**
     * @param $schoolClassId
     * @return SchoolClassStudent
     */
    public function findAllBySchoolClass($schoolClassId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolClassId($schoolClassId)
            ->find();
    }

    /**
     * @return SchoolClassStudent[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()
            ->useStudentQuery()
                ->groupById()
            ->endUse()
            ->find();
    }

    /**
     * @param int $student
     *
     * @return SchoolClassStudent
     */
    public function findByStudent($student)
    {
        return $this->getBaseQuery()->findOneByStudentId($student);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findSchoolClassIdByStudentId($id)
    {
        $studentClass=$this->getBaseQuery()->filterByStudentId($id)->findOne();
        /** @var SchoolClassStudent $studentClass */
        return $studentClass->getSchoolClassId();
    }

    /**
     * @param int $id
     *
     * @return SchoolClassStudent
     */
    public function findByClass($id)
    {
        return $this
            ->getBaseQuery()
                ->filterBySchoolClassId($id)
            ->find();
    }

    /**
     * @return bool|SchoolClassStudent
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolClassStudent();
        }

        return false;
    }

    /**
     * @param SchoolClassStudentQuery $query
     *
     * @return bool|SchoolClassStudentQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin() || $this->isParent()) {
            return $query;
        }

        return $query->filterById($this->getCurrentUser()->getUserProfile()->getSchoolClassStudentId());
    }

    public function limitRole(SchoolClassStudent $schoolClassStudent)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $schoolClassStudent
     * @return bool|mixed
     */
    public function canEdit($schoolClassStudent)
    {
        $schoolClassStudent = $this->findOne($schoolClassStudent);

        if ($this->limitRole($schoolClassStudent)) {
            return false;
        }

        return $schoolClassStudent;
    }

    /**
     * @param mixed $schoolClassStudent
     *
     * @return bool|SchoolClassStudent
     */
    public function canDelete($schoolClassStudent)
    {
        $schoolClassStudent = $this->findOne($schoolClassStudent);

        if ($this->limitRole($schoolClassStudent)) {
            return false;
        }

        return $schoolClassStudent;
    }

    /**
     * @param mixed $schoolClassStudent
     *
     * @return bool|SchoolClassStudent
     */
    public function canView($schoolClassStudent)
    {
        $schoolClassStudent = $this->findOne($schoolClassStudent);

        if ($this->limitRole($schoolClassStudent)) {
            return false;
        }

        return $schoolClassStudent;
    }


}
