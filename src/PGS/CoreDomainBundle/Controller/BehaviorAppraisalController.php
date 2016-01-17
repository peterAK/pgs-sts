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

class BehaviorAppraisalController extends AbstractCoreBaseController
{
    /**
     * @var BehaviorManager
     *
     * @Inject("pgs.core.manager.behavior")
     */
    protected $behaviorManager;

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
     * @var IconManager
     *
     * @Inject("pgs.core.manager.icon")
     */
    protected $iconManager;


    /**
     * @Template("PGSCoreDomainBundle:BehaviorAppraisal:givePoint.html.twig")
     */
    public function givePointAction(Request $request)
    {
        $id             = $request->get('id');

        $user           = $this->getUser()->getUserProfile();
        $schoolClassCourses  = $this->schoolClassCourseManager->findSome();
        $behaviors      = $this->behaviorManager->findAllByPoint();

        $query               = $this->schoolClassStudentManager->findByClass($id);
        $schoolClassStudents = $this->get('knp_paginator');
        $schoolClassStudents = $schoolClassStudents->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            10
        );

        if ($request->getMethod() == "POST") {
            ini_set('date.timezone', 'Asia/Bangkok');
            if (isset($_POST['studentCheckbox']))
            {
                $studentIds=$_POST['studentCheckbox'];
                $selectedBehavior = $request->get('selectedBehavior');
                foreach ($studentIds as $studentId)
                {
                    $schoolClassCourseStudentBehavior = new SchoolClassCourseStudentBehavior();
                    $schoolClassCourseStudentBehavior->setStudentId($studentId);
                    $schoolClassCourseStudentBehavior->setBehaviorId($selectedBehavior);
                    $schoolClassCourseStudentBehavior->setSchoolClassCourseId($id);
                    $schoolClassCourseStudentBehavior->save();
                }

            }
        }

        return [
            'model'               => 'SchoolClassStudent',
            'schoolClassStudents' => $schoolClassStudents,
            'user'                => $user,
            'schoolClassCourses'       => $schoolClassCourses,
            'behaviors'           => $behaviors,
            'classId'             => $id
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:BehaviorAppraisal:review.html.twig")
     */
    public function reviewAction(Request $request)
    {   // find all by day and class
        $user           = $this->getUser()->getUserProfile();
        $selectedClass=$request->get('selectedClass');
        $selectedDay=$request->get('selectedDay');
        $userId= $this->getUser()->getId();
        $schoolClassCourses  = $this->schoolClassCourseManager->findSome();
        if($selectedDay!='all' && $selectedClass=='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSomeByDay($selectedDay);
        }elseif($selectedDay=='all' && $selectedClass!='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findAllByClass($selectedClass);
            $selectedDay='all';
        }elseif($selectedDay=='all' && $selectedClass=='all'){
            $queries=$this->schoolClassCourseStudentBehaviorManager->findSome();
            $selectedDay='all';
        }
        else{
            $queries=$this->schoolClassCourseStudentBehaviorManager->findAllByDayAndClass($selectedDay,$selectedClass);
        }
        $schoolClassCourseStudentBehaviors = $this->get('knp_paginator');
        $schoolClassCourseStudentBehaviors = $schoolClassCourseStudentBehaviors->paginate(
            $queries,
            $request->query->get('pg',1) /*page number*/,
            4
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
            return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_review', ['selectedClass'=>$selectedClass2, 'selectedDay'=>$selectedDay2]));
        }

        return [
            'model'                             => 'SchoolClassStudentBehavior',
            'schoolClassCourses'                => $schoolClassCourses,
            'schoolClassCourseStudentBehaviors' => $schoolClassCourseStudentBehaviors,
            'selectedDay'                       => $selectedDay,
            'selectedClass'                     => $selectedClass,
            'user'                              => $user,
            'negative'                          => $negative,
            'positive'                          => $positive,
            'sum'                               => $sum
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:BehaviorAppraisal:behaviorList.html.twig")
     */
    public function manageBehaviorAction(Request $request)
    {

        $user           = $this->getUser()->getUserProfile();
        $schoolClassCourses  = $this->schoolClassCourseManager->findSome();
        $query          = $this->behaviorManager->findAll();
        $behaviors      = $this->get('knp_paginator');
        $behaviors      = $behaviors->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        return [
            'user'                => $user,
            'schoolClassCourses'       => $schoolClassCourses,
            'behaviors'           => $behaviors
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_TEACHER') || hasRole('ROLE_ADMIN') || hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:BehaviorAppraisal:addBehavior.html.twig")
     */
    public function addBehaviorAction(Request $request)
    {

        $behavior = new Behavior();

        if (!$behavior = $this->behaviorManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_manage_behavior'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $behaviorI18n = new BehaviorI18n();
            $behaviorI18n->setLocale($locale);
            $behavior->addBehaviorI18n($behaviorI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.behavior'), $behavior);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_manage_behavior'));
            }
        }
        $user          = $this->getUser()->getUserProfile();
        $schoolClassCourses = $this->schoolClassCourseManager->findSome();

        return [
            'model'         => 'Behavior',
            'behavior'      => $behavior,
            'user'          => $user,
            'schoolClassCourses' => $schoolClassCourses,
            'form'          => $form->createView()
        ];
    }

    /**
    * @param Form $form
    */
    public function processForm(Form $form)
    {
        /** @var Behavior $behavior*/
        $behavior = $form->getData();
        /** @var Behavior $behavior*/
        $behavior = $form->getData();
        if ($behavior->isNew()) {
            $behavior->setUserId($this->getUser()->getId());
        }
        $behavior->save();
    }

    /**
     * @PreAuthorize("hasRole('ROLE_TEACHER') || hasRole('ROLE_ADMIN') || hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:BehaviorAppraisal:addBehavior.html.twig")
     */
    public function editBehaviorAction(Request $request)
    {
        $id            = $request->get('id');
        $user          = $this->getUser()->getUserProfile();
        $schoolClassCourses = $this->schoolClassCourseManager->findSome();

        if (!$behavior = $this->behaviorManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `behavior` given');
        }

        $form          = $this->createForm($this->get('pgs.core.form.type.behavior'), $behavior);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_manage_behavior'));
            }
        }

        return [
            'model'         => 'Behavior',
            'behavior'      => $behavior,
            'user'          => $user,
            'schoolClassCourses' => $schoolClassCourses,
            'form'          => $form->createView()
        ];
    }


    /**
     * @PreAuthorize("hasRole('ROLE_TEACHER') || hasRole('ROLE_ADMIN') || hasRole('ROLE_PRINCIPAL')")
     *
     */
    public function deleteBehaviorAction(Request $request)
    {
        $id = $request->get('id');

        if (!$behavior = $this->behaviorManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `behavior` given');
        }
        $behavior->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_manage_behavior'));
    }

    /**
     *
     *
     */
//    Hapus Student Behavior
    public function deleteAction(Request $request)
    {
        $studentBehaviorId = $request->get('studentBehaviorId');
        $daySearched = $request->get('daySearched');
        $schoolClassCourseId     = $request->get('schoolClassCourseId');
        if (!$schoolClassCourseStudentBehavior = $this->schoolClassCourseStudentBehaviorManager->canDelete($studentBehaviorId)) {
            throw $this->createNotFoundException('Invalid `Student Behavior` given');
        }
        $schoolClassCourseStudentBehavior->delete();
        return new RedirectResponse($this->generateUrl('pgs_core_behavior_appraisal_review', ['selectedClass'=>$schoolClassCourseId, 'selectedDay'=>$daySearched]));
    }

//    /**
//     * @Template("PGSAdminBundle:Behavior:view.html.twig")
//     */
////    public function viewAction(Request $request)
////    {
////        $id = $request->get('id');
////
////        if (!$behavior = $this->behaviorManager->findOne($id)) {
////            throw $this->createNotFoundException('Invalid `behavior` given');
////        }
////
////        return [
////            'model'    => 'Behavior',
////            'behavior' => $behavior
////        ];
////    }
//
}
