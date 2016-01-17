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
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassPeer;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_class")
 */
class SchoolClassManager extends Authorizer
{
    /**
     * @var SchoolClassQuery
     */
    private $schoolClassQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "schoolClassQuery" = @Inject("pgs.core.query.school_class")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolClassQuery $schoolClassQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->schoolClassQuery = $schoolClassQuery;
    }

    /**
     * @return SchoolClassQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolClassQuery->create();
    }

    /**
     * @return schoolClass
     */
    public function getDefault()
    {
        if(!$schoolClass = $this->getCurrentUser()->getUserProfile()->getSchoolClassId()){
            $schoolClass = new SchoolClass();
        }

        return $schoolClass;
    }

    /**
     * @param mixed $schoolClass
     *
     * @return SchoolClass
     */
    public function findOne($schoolClass)
    {
        if ($schoolClass instanceof SchoolClass) {
            $schoolClass = $schoolClass->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolClass);
    }

    /**
     * @return SchoolClass[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return SchoolClass[]
     */
    public function findAllBySchool()
    {
        return $this->getBaseQuery();
    }

    /**
     * @param string $status
     *
     * @return SchoolClass[]
     */
    public function findSome()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @param int $teacher
     *
     * @return SchoolClass
     */
    public function findByTeacher($teacher)
    {
        return $this->getBaseQuery()->findByPrimaryTeacherId($teacher);
    }


    /**
     * @return int
     */
    public function count()
    {
        return $this->getBaseQuery()->create()->count();
    }

    /**
     * @return bool|SchoolClass
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolClass();
        }

        return false;
    }

    /**
     * @param SchoolClassQuery $query
     *
     * @return bool|SchoolClassQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        elseif ($this->isPrincipal()) {
            return $query
                ->useSchoolClassCourseQuery()
                    ->useSchoolGradeLevelQuery()
                        ->useSchoolQuery()
                            ->filterByOrganizationId($this->getCurrentUser()->getUserProfile()->getOrganizationId())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ;
        }
        elseif($this->isTeacher()){
            $query
                ->useSchoolClassCourseQuery()
                    ->condition(
                        "1",
                        SchoolClassCoursePeer::PRIMARY_TEACHER_ID. " = " . $this->getCurrentUser()->getId()
                    )
                    ->condition(
                        "2",
                        SchoolClassCoursePeer::SECONDARY_TEACHER_ID. " = " . $this->getCurrentUser()->getId()
                    )
                ->endUse()
            ;

            return $query->having([1,2], "or");
        }
        elseif ($this->isStudent()) {
            return $query
                ->useSchoolClassStudentQuery()
                    ->useStudentQuery()
                        ->filterByUserId($this->getCurrentUser()->getId())
                    ->endUse()
                ->endUse()
            ;
        }
        elseif ($this->isParent()) {
            return $query
                ->useSchoolClassStudentQuery()
                    ->useStudentQuery()
                        ->useParentStudentQuery()
                            ->filterByUserId($this->activePreference->getCurrentUserProfile()->getId())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ;
        }
        else{
            echo('Please Login or Contact your Teacher / Principal / Administrator');die;
        }

//        return $query->filterById($this->getCurrentUser()->getUserProfile()->getSchoolClassId());
    }

    public function limitRole(SchoolClass $schoolClass)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $schoolClass
     *
     * @return bool|SchoolClass
     */
    public function canEdit($schoolClass)
    {
        $schoolClass = $this->findOne($schoolClass);

        if ($this->limitRole($schoolClass)) {
            return false;
        }

        return $schoolClass;
    }

    /**
     * @param mixed $schoolClass
     *
     * @return bool|SchoolClass
     */
    public function canDelete($schoolClass)
    {
        $schoolClass = $this->findOne($schoolClass);

        if ($this->limitRole($schoolClass)) {
            return false;
        }

        return $schoolClass;
    }

    /**
     * @param mixed $schoolClass
     *
     * @return bool|SchoolClass
     */
    public function canView($schoolClass)
    {
        $schoolClass = $this->findOne($schoolClass);

        if ($this->limitRole($schoolClass)) {
            return false;
        }

        return $schoolClass;
    }


}
