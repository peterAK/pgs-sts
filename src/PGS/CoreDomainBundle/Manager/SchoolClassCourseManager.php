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
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourseQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_class_course")
 */
class SchoolClassCourseManager extends Authorizer
{
    /**
     * @var SchoolClassCourseQuery
     */
    private $schoolClassCourseQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"        = @Inject("pgs.core.container.active_preference"),
     *      "schoolClassCourseQuery"  = @Inject("pgs.core.query.school_class_course")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolClassCourseQuery $schoolClassCourseQuery
    ) {
        $this->activePreference       = $activePreference;
        $this->schoolClassCourseQuery = $schoolClassCourseQuery;
    }

    /**
     * @return SchoolClassCourseQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolClassCourseQuery->create();
    }

    /**
     * @return schoolClassCourse
     */
    public function getDefault()
    {
        if(!$schoolClassCourse = $this->getCurrentUser()->getUserProfile()->getSchoolClassCourseId()){
            $schoolClassCourse = new SchoolClassCourse();
        }

        return $schoolClassCourse;
    }

    /**
     * @param mixed $schoolClassCourse
     *
     * @return SchoolClassCourse
     */
    public function findOne($schoolClassCourse)
    {
        if ($schoolClassCourse instanceof SchoolClassCourse) {
            $schoolClassCourse = $schoolClassCourse->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolClassCourse);
    }

    /**
     * @param $schoolClassId
     * @return SchoolClassCourse
     */
    public function findAllBySchoolClass($schoolClassId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolClassId($schoolClassId)
            ->find();
    }

    /**
     * @return SchoolClassCourse[]
     */
    public function findAllBySchool()
    {
        return $this->getBaseQuery();
    }

    /**
     * @return SchoolClassCourse[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return SchoolClassCourse[]
     */
    public function findSome()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @param int $teacher
     *
     * @return SchoolClassCourse
     */
    public function findByTeacher($teacher)
    {
        return $this->getBaseQuery()->findByPrimaryTeacherId($teacher);
    }

    /**
     * @param int $id
     *
     * @return SchoolClassCourse
     */
    public function findBySchoolClassId($id)
    {
        return $this->getBaseQuery()->findBySchoolClassId($id);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getBaseQuery()->create()->count();
    }


    /**
     * @param SchoolClassCourse $schoolClassCourse
     * @param string $direction
     *
     * @return true
     */
    public function moveSchoolClassCourse(SchoolClassCourse $schoolClassCourse, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $schoolClassCourse->moveToTop();
                break;

            case 'UP':
                $schoolClassCourse->moveUp();
                break;

            case 'DOWN':
                $schoolClassCourse->moveDown();
                break;

            case 'BOTTOM':
                $schoolClassCourse->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @return bool|SchoolClassCourse
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolClassCourse();
        }

        return false;
    }

    /**
     * @param SchoolClassCourseQuery $query
     *
     * @return bool|SchoolClassCourseQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        elseif ($this->isPrincipal()) {
            return $query
                ->useCourseQuery()
                    ->useSchoolQuery()
                        ->filterByOrganizationId($this->getCurrentUser()->getUserProfile()->getOrganizationId())
                    ->endUse()
                ->endUse()
                ;
        }
        elseif($this->isTeacher()){
            $query
                ->condition(
                    "1",
                    SchoolClassCoursePeer::PRIMARY_TEACHER_ID. " = " . $this->getCurrentUser()->getId()
                )
                ->condition(
                    "2",
                    SchoolClassCoursePeer::SECONDARY_TEACHER_ID. " = " . $this->getCurrentUser()->getId()
                )
            ;

            return $query->having([1,2], "or");
        }
        elseif ($this->isStudent()) {
            return $query
                ->useSchoolClassQuery()
                    ->useSchoolClassStudentQuery()
                        ->useStudentQuery()
                            ->filterByUserId($this->getCurrentUser()->getId())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ;
        }
        elseif ($this->isParent()) {
            return $query
                ->useSchoolClassQuery()
                    ->useSchoolClassStudentQuery()
                        ->useStudentQuery()
                            ->useParentStudentQuery()
                                ->filterByUserId($this->activePreference->getCurrentUserProfile()->getId())
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ;
        }
        else{
            echo('Please Login or Contact your Teacher / Principal / Administrator');die;
        }

//        return $query->filterById($this->getCurrentUser()->getUserProfile()->getSchoolClassCourseId());
    }

    public function limitRole(SchoolClassCourse $schoolClassCourse)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $schoolClassCourse
     *
     * @return bool|SchoolClassCourse
     */
    public function canEdit($schoolClassCourse)
    {
        $schoolClassCourse = $this->findOne($schoolClassCourse);

        if ($this->limitRole($schoolClassCourse)) {
            return false;
        }

        return $schoolClassCourse;
    }

    /**
     * @param mixed $schoolClassCourse
     *
     * @return bool|SchoolClassCourse
     */
    public function canDelete($schoolClassCourse)
    {
        $schoolClassCourse = $this->findOne($schoolClassCourse);

        if ($this->limitRole($schoolClassCourse)) {
            return false;
        }

        return $schoolClassCourse;
    }

    /**
     * @param mixed $schoolClassCourse
     *
     * @return bool|SchoolClassCourse
     */
    public function canView($schoolClassCourse)
    {
        $schoolClassCourse = $this->findOne($schoolClassCourse);

        if ($this->limitRole($schoolClassCourse)) {
            return false;
        }

        return $schoolClassCourse;
    }


}
