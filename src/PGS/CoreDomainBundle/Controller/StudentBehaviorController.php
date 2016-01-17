<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use PGS\CoreDomainBundle\Manager\BehaviorManager;
use PGS\CoreDomainBundle\Manager\IconManager;
use PGS\CoreDomainBundle\Manager\StudentManager;
use PGS\CoreDomainBundle\Manager\StudentAvatarManager;
use PGS\CoreDomainBundle\Manager\ParentStudentManager;
use PGS\CoreDomainBundle\Manager\SchoolClassCourseStudentBehaviorManager;
use PGS\CoreDomainBundle\Manager\SchoolClassCourseManager;
use PGS\CoreDomainBundle\Manager\SchoolClassStudentManager;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\SchoolClassCourseStudentBehavior\SchoolClassCourseStudentBehavior;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorI18n;
use PGS\CoreDomainBundle\Controller\AbstractCoreBaseController;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use PHPPdf\Core\Node\Chart\PieChart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class StudentBehaviorController extends AbstractCoreBaseController
{

    /**
     * @var BehaviorManager
     *
     * @Inject("pgs.core.manager.behavior")
     */
    protected $behaviorManager;

    /**
     * @var StudentManager
     *
     * @Inject("pgs.core.manager.student")
     */
    protected $studentManager;

    /**
     * @var StudentAvatarManager
     *
     * @Inject("pgs.core.manager.student_avatar")
     */
    protected $studentAvatarManager;

    /**
     * @var IconManager
     *
     * @Inject("pgs.core.manager.icon")
     */
    protected $iconManager;

    /**
     * @var ParentStudentManager
     *
     * @Inject("pgs.core.manager.parent_student")
     */
    protected $parentStudentManager;

    /**
     * @var SchoolClassCourseStudentBehaviorManager
     *
     * @Inject("pgs.core.manager.schoolClassCourseStudentBehavior")
     */
    protected $schoolClassCourseStudentBehaviorManager;

    /**
     * @var SchoolClassCourseManager
     *
     * @Inject("pgs.core.manager.schoolClassCourse")
     */
    protected $schoolClassCourseManager;

    /**
     * @var SchoolClassStudentManager
     *
     * @Inject("pgs.core.manager.schoolClassStudent")
     */
    protected $schoolClassStudentManager;

    /**
     * @Template("PGSCoreDomainBundle:StudentBehavior:list.html.twig")
     */
    public function listAction(Request $request)
    {
        if($this->isGranted('ROLE_PARENT')){
            $studentId= $request->get('studentId');
        }elseif($this->isGranted('ROLE_STUDENT')){
            $studentId= $this->getUser()->getId();
        }
        if($studentId==null){
            $studentId=0;
            $studentClass=0;
        }
        else{
            $studentClass  = $this->schoolClassStudentManager->findSchoolClassIdByStudentId($studentId);
        }
        $schoolClassCourses  = $this->schoolClassCourseManager->findBySchoolClassId($studentClass);
        $children  = $this->parentStudentManager->findSome();
        $queries = $this->schoolClassCourseStudentBehaviorManager->findSomeByDay(0);
        $schoolClassCourseStudentBehaviors = $this->get('knp_paginator');
        $schoolClassCourseStudentBehaviors = $schoolClassCourseStudentBehaviors->paginate(
            $queries,
            $request->query->get('pg',1) /*page number*/,
            5
        );
        $day=0;
        $positive=0; $negative=0;
        foreach ($queries as $query)
        {
            if($query->getBehavior()->getType()=='negative'){
                $negative=$negative+$query->getBehavior()->getPoint();
            }else{
                $positive=$positive+$query->getBehavior()->getPoint();
            }
        }
        $sum=$negative+$positive;

        if ($request->getMethod() == "POST") {
            $selectedClass=$request->get('selectedClass');
            $selectedDay=$request->get('selectedDay');
            $studentId=$request->get('studentId');
            return new RedirectResponse($this->generateUrl('pgs_core_student_behavior_search_list', ['selectedClass'=>$selectedClass, 'selectedDay'=>$selectedDay, 'studentId'=>$studentId]));
        }
        return [
            'model'                             => 'SchoolClassCourseStudentBehavior',
            'schoolClassCourseStudentBehaviors' => $schoolClassCourseStudentBehaviors,
            'schoolClassCourses'                => $schoolClassCourses,
            'studentId'                         => $studentId,
            'children'                          => $children,
            'negative'                          => $negative,
            'positive'                          => $positive,
            'sum'                               => $sum,
            'day'                               => $day
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:StudentBehavior:list.html.twig")
     */
    public function searchlistAction(Request $request)
    {
        $selectedClass=$request->get('selectedClass');
        $selectedDay=$request->get('selectedDay');
        if($this->isGranted('ROLE_PARENT')){
            $studentId= $request->get('studentId');
        }elseif($this->isGranted('ROLE_STUDENT')){
            $studentId= $this->getUser()->getId();
        }
        $studentClass  = $this->schoolClassStudentManager->findSchoolClassIdByStudentId($studentId);
        $schoolClassCourses  = $this->schoolClassCourseManager->findBySchoolClassId($studentClass);
        $children  = $this->parentStudentManager->findSome();
        if($selectedClass=='all' && $selectedDay!='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSomeByDay($selectedDay);
        }elseif($selectedDay=='all' && $selectedClass!='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSomeByClassAndStudentId($selectedClass,$studentId);
        }elseif($selectedDay=='all' && $selectedClass=='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSome();
        }
        else{
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSomeByDayAndClassAndUserId($selectedDay,$selectedClass,$studentId);
        }
        $schoolClassCourseStudentBehaviors = $this->get('knp_paginator');
        $schoolClassCourseStudentBehaviors = $schoolClassCourseStudentBehaviors->paginate(
            $queries,
            $request->query->get('pg',1) /*page number*/,
            5
        );
        $positive=0; $negative=0;
        foreach ($queries as $query)
        {
            if($query->getBehavior()->getType()=='negative'){
                $negative=$negative+$query->getBehavior()->getPoint();
            }else{
                $positive=$positive+$query->getBehavior()->getPoint();
            }
        }
        $sum=$negative+$positive;

        if ($request->getMethod() == "POST") {
            $selectedClass2=$_POST['selectedClass'];
            $selectedDay2=$_POST['selectedDay'];
            $studentId2=$request->get('studentId');
            return new RedirectResponse($this->generateUrl('pgs_core_student_behavior_search_list', ['selectedClass'=>$selectedClass2, 'selectedDay'=>$selectedDay2, 'studentId'=>$studentId2]));
        }

        return [
            'model'                             => 'SchoolClassStudentBehavior',
            'schoolClassCourses'                => $schoolClassCourses,
            'schoolClassCourseStudentBehaviors' => $schoolClassCourseStudentBehaviors,
            'studentId'                         => $studentId,
            'children'                          => $children,
            'negative'                          => $negative,
            'positive'                          => $positive,
            'sum'                               => $sum,
            'day'                               => $selectedDay
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:StudentBehavior:rank.html.twig")
     */
    public function rankAction(Request $request)
    {
        $schoolClassId=$request->get('id');
        if($schoolClassId=='all'){
            $ranks          = $this->schoolClassCourseStudentBehaviorManager->countTotalBehaviorPointRank();
        }else{
            $ranks          = $this->schoolClassCourseStudentBehaviorManager->countTotalBehaviorPointRankByClass($schoolClassId);
        }
        $studentClass       = $this->schoolClassStudentManager->findSchoolClassIdByStudentId($this->getUser()->getId());
        $schoolClassCourses = $this->schoolClassCourseManager->findBySchoolClassId($studentClass);
        $studentAvatars     = $this->studentAvatarManager->findAll();


        if ($request->getMethod() == "POST") {
            $selectedClass=$_POST['id'];
            return new RedirectResponse($this->generateUrl('pgs_core_student_behavior_rank', ['id'=>$selectedClass]));
        }
        $myId=$this->getUser()->getId();




        return [
            'model'                 => 'Students',
            'schoolClassCourses'    => $schoolClassCourses,
            'ranks'                 => $ranks,
            'schoolClassId'         => $schoolClassId,
            'studentAvatars'        => $studentAvatars,
            'myId'                  =>$myId
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:StudentBehavior:friend.html.twig")
     */
    public function friendAction(Request $request)
    {
        $myId           = $this->getUser()->getId();
        $studentAvatars = $this->studentAvatarManager->findAll();
        $studentClass   = $this->schoolClassStudentManager->findSchoolClassIdByStudentId($this->getUser()->getId());
        $query          = $this->schoolClassStudentManager->findByClass($studentClass);
        $friends        = $this->get('knp_paginator');
        $friends        = $friends->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            5
        );
        return [
            'model'          => 'Friends',
            'friends'        => $friends,
            'studentAvatars' => $studentAvatars,
            'myId'           => $myId
        ];
    }

}