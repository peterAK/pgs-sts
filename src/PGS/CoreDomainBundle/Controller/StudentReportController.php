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
use PGS\CoreDomainBundle\Manager\StudentReportManager;
use PGS\CoreDomainBundle\Manager\SchoolClassManager;
use PGS\CoreDomainBundle\Manager\ScoreManager;
use PGS\CoreDomainBundle\Manager\SchoolClassStudentManager;
use PGS\CoreDomainBundle\Manager\SchoolClassCourseManager;
use PGS\CoreDomainBundle\Manager\StudentHistoryManager;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class StudentReportController extends AbstractCoreBaseController
{
    /**
     * @var StudentReportManager
     *
     * @Inject("pgs.core.manager.student_report")
     */
    protected $studentReportManager;

   /**
     * @var SchoolClassStudentManager
     *
     * @Inject("pgs.core.manager.school_class_student")
     */
    protected $schoolClassStudentManager;

   /**
     * @var SchoolClassCourseManager
     *
     * @Inject("pgs.core.manager.school_class_course")
     */
    protected $schoolClassCourseManager;

   /**
     * @var SchoolClassManager
     *
     * @Inject("pgs.core.manager.school_class")
     */
    protected $schoolClassManager;

    /**
     * @var ScoreManager
     *
     * @Inject("pgs.core.manager.score")
     */
    protected $scoreManager;

    /**
     * @var StudentHistoryManager
     *
     * @Inject("pgs.core.manager.student.history")
     */
    protected $studentHistoryManager;

    /**
     * @Template("PGSCoreDomainBundle:StudentReport:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $studentReports = $this->studentReportManager->findAll();

        return [
            'model'          => 'Student Report',
            'studentReports' => $studentReports
        ];
    }

    /**
//     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')  or hasRole(' ROLE_STUDENT')")
     * @Template("PGSCoreDomainBundle:StudentReport:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

//        if (!$studentReport = $this->studentReportManager->findOne($id)) {
//            throw $this->createNotFoundException('Invalid `student report` given');
//        }
//
//        return [
//            'model'         => 'Student Report',
//            'studentReport' => $studentReport
//        ];
        $schoolClassStudent = $this->schoolClassStudentManager->findOneByStudent($id);
        $scores = $this->scoreManager->findAllByStudent($id);
        $statuses = $this->studentHistoryManager->getStatuses();
//        $studentHistory = $studentHistory = $this->studentHistoryManager->findOneByStudent($studentId);
//        $studentStatus = $studentHistory->getStatus();

        return [
            'title'              => 'New Report Student',
            'model'              => 'Student',
            'scores'             => $scores,
            'schoolClassStudent' => $schoolClassStudent,
            'statuses'           => $statuses,
//            'studentHistory' => $studentStatus,
            'studentHistory'     => "new",
            'student'            => $id
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentReport:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $studentReport = new StudentReport();

        if (!$studentReport = $this->studentReportManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_student_report_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.studentReport'), $studentReport);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_student_report_score'));
            }
        }

        return [
            'model'         => 'Student Report',
            'studentReport' => $studentReport,
            'form'          => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:StudentReport:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$studentReport = $this->studentReportManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `student report` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.studentReport'), $studentReport);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_student_report_list'));
            }
        }

        return [
            'model'         => 'Student Report',
            'studentReport' => $studentReport,
            'form'          => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$studentReport = $this->studentReportManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `student report` given');
        }

        $studentReport->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_student_report_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var StudentReport $studentReport*/
        $studentReport = $form->getData();
        $studentReport->save();
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentReport:list_score.html.twig")
     */
    public function selectByClassAction(Request $request)
    {

        $schoolClass   = $this->schoolClassManager->findAllBySchool()->find();
//        $schoolClassStudent = $this->schoolClassStudentManager->findAll();

        if ($request->getMethod() == "POST") {

            $schoolClassId=$_POST['selectedClass'];
//            $schoolClassStudentId=$_POST['selectedStudent'];
//            return new RedirectResponse($this->generateUrl('pgs_core_score_list_by_class', ['schoolClassId' => $schoolClassId] ));
            return new RedirectResponse($this->generateUrl('pgs_core_student_report_choose_student', ['schoolClassId' => $schoolClassId] ));

        }

        return [

            'model'         => 'Student Report',
            'schoolClasses' => $schoolClass,
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentReport:choose_student.html.twig")
     */
    public function chooseStudentAction(Request $request)
    {
        $schoolClassId = $request->get('schoolClassId');
        $schoolClassStudents = $this->schoolClassStudentManager->findAllBySchoolClass($schoolClassId);
        if ($request->getMethod() == "POST") {

            $studentId=$_POST['selectedStudent'];
            return new RedirectResponse($this->generateUrl('pgs_core_student_report_report', ['studentId' => $studentId] ));

        }
        return [
            'title'  => 'New Report Student',
            'model'  => 'Student list',
            'schoolClassStudents'  => $schoolClassStudents,
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentReport:report_student.html.twig")
     */
    public function reportAction(Request $request)
    {
        $studentId = $request->get('studentId');
        $schoolClassStudent = $this->schoolClassStudentManager->findOneByStudent($studentId);
        $scores = $this->scoreManager->findAllByStudent($studentId);
        $statuses = $this->studentHistoryManager->getStatuses();
//        $studentHistory = $studentHistory = $this->studentHistoryManager->findOneByStudent($studentId);
//        $studentStatus = $studentHistory->getStatus();

        return [
            'title'              => 'New Report Student',
            'model'              => 'Student',
            'scores'             => $scores,
            'schoolClassStudent' => $schoolClassStudent,
            'statuses'           => $statuses,
//            'studentHistory' => $studentStatus,
            'studentHistory'     => "new",
            'student'            => $studentId
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:StudentReport:report_student.html.twig")
     */
    public function approveStatusAction(Request $request)
    {
        $studentId          = $request->get('studentId');
        $status             = $request->get('status');
        $schoolClassStudent = $this->schoolClassStudentManager->findOneByStudent($studentId);
        $scores             = $this->scoreManager->findAllByStudent($studentId);
        $studentHistory     = $this->studentHistoryManager->findOneByStudent($studentId);
        $studentHistory->setStatus($status)->save();
        $statuses           = $this->studentHistoryManager->getStatuses();


        return [
            'title'              => 'New Report Student',
            'model'              => 'Student',
            'scores'             => $scores,
            'schoolClassStudent' => $schoolClassStudent,
            'statuses'           => $statuses,
            'studentHistory'     => $status

        ];
    }
}
