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

use Carbon\Carbon;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCoursePeer;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\om\BaseSchoolClassCourseStudentBehaviorQuery;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehaviorQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_class_course_student_behavior")
 */
class SchoolClassCourseStudentBehaviorManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolClassCourseStudentBehaviorQuery
     */
    private $schoolClassCourseStudentBehaviorQuery;

    /**
     * @InjectParams({
     *      "schoolClassCourseStudentBehaviorQuery" = @Inject("pgs.core.query.school_class_course_student_behavior"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        schoolClassCourseStudentBehaviorQuery $schoolClassCourseStudentBehaviorQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    )
    {
        $this->schoolClassCourseStudentBehaviorQuery          = $schoolClassCourseStudentBehaviorQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return SchoolClassCourseStudentBehaviorQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolClassCourseStudentBehaviorQuery->create();
    }

    /**
     * @param mixed $schoolClassStudentBehavior
     *
     * @return SchoolClassCourseStudentBehavior
     */
    public function findOne($schoolClassCourseStudentBehavior)
    {
        if ($schoolClassCourseStudentBehavior instanceof SchoolClassCourseStudentBehavior) {
            $schoolClassCourseStudentBehavior = $schoolClassCourseStudentBehavior->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolClassCourseStudentBehavior);
    }

    /**
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderByStudentId()->find();
    }

    /**
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findSome()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findAllByStudentId($studentId)
    {
        return $this->getBaseQuery()->filterByStudentId($studentId)->orderBySchoolClassCourseId()->find();
    }

    /**
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findAllByClass($schoolClassCourse)
    {
        return $this->getBaseQuery()->filterBySchoolClassCourseId($schoolClassCourse)->find();
    }

    /**
     *
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findAllByDayAndClass($day,$schoolClassCourseId)
    {
        ini_set('date.timezone', 'Asia/Bangkok');
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $start = date('Y-m-d H:i:s');
        $end=date('Y-m-d H:i:s', strtotime('-'.$day.' day', $today));
        return $this->getBaseQuery()
            ->filterByCreatedAt(['max'=>$start,'min'=>$end])
            ->filterBySchoolClassCourseId($schoolClassCourseId)
            ->orderByStudentId()
            ->find();
    }
    /**
    *
    * @return SchoolClassCourseStudentBehavior[]
    */
    public function findSomeByDayAndClassAndUserId($day,$schoolClassCourseId, $userId)
    {

        ini_set('date.timezone', 'Asia/Bangkok');
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $start = date('Y-m-d H:i:s');
        $end=date('Y-m-d H:i:s', strtotime('-'.$day.' day', $today));
        return $this->limitList($this->getBaseQuery()
            ->filterByCreatedAt(['max'=>$start,'min'=>$end])
            ->filterBySchoolClassCourseId($schoolClassCourseId)
            ->filterByStudentId($userId))
            ->orderByStudentId()
            ->find();
    }

    public function findSomeByClassAndStudentId($schoolClassCourseId, $studenetId)
    {
        return $this->limitList($this->getBaseQuery()
            ->filterBySchoolClassCourseId($schoolClassCourseId)
            ->filterByStudentId($studenetId))
            ->orderByCreatedAt()
            ->find();
    }

    /**
     *
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function findAllByDay($day)
    {
        ini_set('date.timezone', 'Asia/Bangkok');
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $start = date('Y-m-d H:i:s');
        $end=date('Y-m-d H:i:s', strtotime('-'.$day.' day', $today));
        return $this->getBaseQuery()
            ->filterByCreatedAt(['max'=>$start,'min'=>$end])
            ->orderByStudentId()
            ->find();
    }

   /**
    *
    * @return SchoolClassCourseStudentBehavior[]
    */
    public function findSomeByDay($day)
    {
        ini_set('date.timezone', 'Asia/Bangkok');
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $start = date('Y-m-d H:i:s');
        $end=date('Y-m-d H:i:s', strtotime('-'.$day.' day', $today));
        return $this->limitList($this->getBaseQuery()
            ->filterByCreatedAt(['max'=>$start,'min'=>$end]))
            ->find();

    }

    /**
     * @param mixed $schoolClassCourseStudentBehavior
     */
    public function setActive($schoolClassCourseStudentBehavior)
    {
        if ($schoolClassCourseStudentBehavior instanceof SchoolClassCourseStudentBehavior)
        {
            $schoolClassCourseStudentBehavior = $schoolClassCourseStudentBehavior->getId();
        }

        $this->getBaseQuery()->setActive($schoolClassCourseStudentBehavior);
    }

    /**
     * @param SchoolClassCourseStudentBehaviorQuery $query
     * @return bool|SchoolClassCourseStudentBehaviorQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        elseif ($this->isPrincipal()) {
            return $query
                ->useSchoolClassCourseQuery()
                    ->useCourseQuery()
                        ->useSchoolQuery()
                            ->filterByOrganizationId($this->getCurrentUser()->getUserProfile()->getOrganizationId())
                        ->endUse()
                    ->endUse()
                ->endUse()
                ;
        }
        elseif($this->isTeacher()){
            return $query
                ->joinSchoolClassCourse()
                ->withColumn("school_class_course.primary_teacher_id", "guruUtama")
                ->withColumn("school_class_course.secondary_teacher_id", "guruKedua")
                ->having("guruUtama = ".$this->getCurrentUser()->getId(). " OR ". "guruKedua = ".$this->getCurrentUser()->getId())
                ;
        }
        elseif ($this->isStudent()) {
            return $query
                ->filterByStudentId($this->activePreference->getCurrentUserProfile()->getId())
                ;
        }
        elseif ($this->isParent()) {
            return $query
                ->useStudentQuery()
                    ->useParentStudentQuery()
                        ->filterByUserId($this->activePreference->getCurrentUserProfile()->getId())
                    ->endUse()
                ->endUse()
                ;
        }
        else{
            echo('Please Login or Contact your Teacher / Principal / Administrator');die;
        }
    }

    public function limitRole(SchoolClassCourseStudentBehavior $schoolClassCourseStudentBehavior)
    {
        if ($this->isAdmin() || $this->isParent() ||  $this->isTeacher()){
            return false;
        }

    }

    /**
     * @param bool|SchoolClassCourseStudentBehavior
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new SchoolClassCourseStudentBehavior();
        }
        return false;
    }

    /**
     * @param mixed $schoolClassCourseStudentBehavior
     * @return bool|mixed|SchoolClassCourseStudentBehavior
     */

    public function canEdit($schoolClassCourseStudentBehavior)
    {
        $schoolClassCourseStudentBehavior = $this->findOne($schoolClassCourseStudentBehavior);
        if($this->limitRole($schoolClassCourseStudentBehavior)){
            return false;
        }

        return $schoolClassCourseStudentBehavior;
    }

    /**
     * @param mixed $schoolClassCourseStudentBehavior
     * @return bool|mixed|SchoolClassCourseStudentBehavior
     */
    public function canDelete($schoolClassCourseStudentBehavior)
    {
        $schoolClassCourseStudentBehavior = $this->findOne($schoolClassCourseStudentBehavior);
        if($this->limitRole($schoolClassCourseStudentBehavior)){
            return false;
        }

        return $schoolClassCourseStudentBehavior;
    }

    /**
     * @param mixed $schoolClassCourseStudentBehavior
     * @return bool|mixed|SchoolClassCourseStudentBehavior
     */
    public function canView($schoolClassCourseStudentBehavior)
    {
        $schoolClassCourseStudentBehavior = $this->findOne($schoolClassCourseStudentBehavior);
        if($this->limitRole($schoolClassCourseStudentBehavior)){
            return false;
        }

        return $schoolClassCourseStudentBehavior;
    }

    /**
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function countTotalBehaviorPointRank()
    { //batesin per grade level
        return $this->getBaseQuery()
            ->groupByStudentId()
                ->joinBehavior()
                    ->withColumn("SUM(behavior.point)", "totalPoint")
                ->orderBy("totalPoint",\Criteria::DESC)
            ->find()
        ;
    }

    /**
     * @param mixed $id
     * @return SchoolClassCourseStudentBehavior[]
     */
    public function countTotalBehaviorPointRankByClass($id)
    {
        return $this->getBaseQuery()
            ->filterBySchoolClassCourseId($id)
            ->groupByStudentId()
            ->joinBehavior()
            ->withColumn("SUM(behavior.point)", "totalPoint")
            ->orderBy("totalPoint",\Criteria::DESC)
            ->find();
    }
    /**
     * @param mixed $id
     * @return SchoolClassCourseStudentBehavior
     */
    public function countMyTotalBehaviorPoint()
    {
        return $this->getBaseQuery()
            ->filterByStudentId($this->getCurrentUser()->getId())
            ->groupByStudentId()
            ->joinBehavior()
            ->withColumn("SUM(behavior.point)", "totalPoint")
            ->findOne();
    }

}
