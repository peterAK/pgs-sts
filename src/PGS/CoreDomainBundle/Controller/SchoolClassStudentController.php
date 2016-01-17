<?php

/**
 * This file is part of the PGS/AdminBundle package.
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
use PGS\CoreDomainBundle\Manager\SchoolClassStudentManager;
use PGS\CoreDomainBundle\Manager\SchoolClassManager;
use PGS\CoreDomainBundle\Manager\StudentManager;
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\SchoolClassStudent\SchoolClassStudent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolClassStudentController extends AbstractCoreBaseController
{
    /**
     * @var SchoolClassStudentManager
     * @Inject("pgs.core.manager.school_class_student")
     */
    protected $schoolClassStudentManager;

    /**
     * @var SchoolClassManager
     * @Inject("pgs.core.manager.school_class")
     */
    protected $schoolClassManager;

    /**
     * @var StudentManager
     * @Inject("pgs.core.manager.student")
     */
    protected $studentManager;

    /**
     * @Template("PGSCoreDomainBundle:SchoolClassStudent:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolClassStudents  = $this->schoolClassStudentManager->findAll();
//        $statuses = $this->schoolClassManager->getStatuses();
//        $active   = $request->get('active','All');

        return [
            'model'                => 'School Class Students',
            'schoolClassStudents'  => $schoolClassStudents,
//            'statuses'             => $statuses,
//            'active'               => $active
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolClassStudent:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassStudent = $this->schoolClassStudentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class student` given');
        }

        return [
            'model'              => 'School Class Student',
            'schoolClassStudent' => $schoolClassStudent
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClassStudent:form.html.twig")
     */
    public function newAction(Request $request)
    {

//        if($request->get('schoolClassId') != null){
//            $classId = $request->get('schoolClassId');
//
//        }

        $schoolClassStudent = new SchoolClassStudent();
        $schoolClass   = $this->schoolClassManager->findAllBySchool()->find();
        $student       = $this->studentManager->findAllBySchool();

//
//
//        $user           = $this->getUser()->getUserProfile();
//        $schoolClasses  = $this->schoolClassManager->findSome();
//        $schoolClassStudents = $this->schoolClassStudentManager->findAllBySchool();

        if ($request->getMethod() == "POST") {
            if (isset($_POST['studentCheckbox']))
            {
                $studentIds=$_POST['studentCheckbox'];
                $selectedClass = $request->get('selectedClass');
                foreach ($studentIds as $studentId)
                {
                    $schoolClassStudent = new SchoolClassStudent();
                    $schoolClassStudent->setSchoolClassId($selectedClass);
                    $schoolClassStudent->setStudentId($studentId);
                    $schoolClassStudent->save();
                    return new RedirectResponse($this->generateUrl('pgs_core_school_class_student_list'));

                }

            }
        }

        return [
            'model'              => 'School Class Student',
            'schoolClassStudent' => $schoolClassStudent,
            'schoolClasses'      => $schoolClass,
            'students'           => $student,
//            'form'               => $form->createView()
        ];
    }

//    /**
//     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
//     *
//     * @Template("PGSCoreDomainBundle:SchoolClassStudent:form.html.twig")
//     *
//     */
//    public function choiceClassAction(Request $request)
//    {
//
//        $user           = $this->getUser()->getUserProfile();
//        $schoolClasses  = $this->schoolClassManager->findSome();
//        $schoolClassStudents = $this->schoolClassStudentManager->findAllBySchool();
//
//        if ($request->getMethod() == "POST") {
//            if (isset($_POST['studentCheckbox']))
//            {
//                $studentIds=$_POST['studentCheckbox'];
//                $selectedClass = $request->get('selectedClass');
//                foreach ($studentIds as $studentId)
//                {
//                    $schoolClassStudent = new SchoolClassStudent();
//                    $schoolClassStudent->setSchoolClassId($selectedClass);
//                    $schoolClassStudent->setStudentId($studentId);
//                    $schoolClassStudent->save();
//                }
//
//            }
//        }
//    }


    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClassStudent:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassStudent = $this->schoolClassStudentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class student` given');
        }

//        if (!$this->schoolManager->canEdit($schoolClass)) {
//            return $this->createAccessDeniedException('Unauthorized access');
//        }

        $form = $this->createForm($this->get('pgs.core.form.type.schoolClassStudent'), $schoolClassStudent);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_class_student_list'));
            }
        }

        return [
            'model'              => 'School Class Student',
            'schoolClassStudent' => $schoolClassStudent,
            'form'               => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassStudent = $this->schoolClassStudentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class student` given');
        }

        if ($this->schoolClassStudentManager->findOne($schoolClassStudent)) {
            $schoolClassStudent->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_student_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolClassStudent $schoolClassStudent*/
        $schoolClassStudent = $form->getData();
        $schoolClassStudent->save();
    }

//    /**
//     * @Template("PGSCoreDomainBundle:SchoolClass:list_by_status.html.twig")
//     * @param int $status
//     *
//     * @return Response
//     */
//    public function viewSchoolClassByStatusAction($status)
//    {
//        $schoolClasses = [];
//        $schoolClasses = $this->schoolClassManager->findByStatus($status);
//
//        return [ 'schoolClasses' => $schoolClasses ];
//    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function moveAction(Request $request)
    {
        $id = $request->get('id', null);
        $direction = $request->get('direction', null);

        if ($direction === null) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_class_student_list'));
        }

        if (!$schoolClassStudent= $this->schoolClassStudentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class student` given');
        }

        $this->schoolClassStudentManager->moveSchoolClassStudent($schoolClassStudent, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_student_list'));
    }

    public function fetchRecordAction(Request $request)
    {
        $schoolClass = $request->get('schoolClassId');


        if ($request->getMethod() == "POST") {
            $data        = $request->get('data');
            $StudentId = $data['student_id'];

            $this->toggleStudent($schoolClass, $studentId);
        }

        $output = [
            "data"            => [],
            "options"         => [],
            "recordsTotal"    => "0",
            "recordsFiltered" => "0"
        ];

        if ($schoolClass         = $this->schoolClassManager->findOne($schoolHealth)) {
            $studentId           = $this->studentManager->findAllBySchool();
            $schoolClassStudent  = $this->schoolClassStudentManager->findAllBySchoolClass($schoolClass);

            $total = $this->studentManager->findAllBySchool()->count();
            $data  = [];
            $i     = 0;

            /** @var Student $student */
            foreach ($students as $student) {
                $i++;
                $studentFound = 0;

                /** @var SchoolClassStudent $schoolClassStudent */
                foreach ($schoolClassStudents as $schoolClassStudent) {
                    if ($student->getId() == $schoolClassStudent->getStudentId()) {
                        $studentFound = 1;
                    }
                }

                $data[] = [
                    "DT_RowId"         => "row_" . $i,
                    "school_class_id"  => $schoolHealth,
                    "student_id"       => $student->getId(),
                    "available"        => $studentFound,
                    "student"          => $student->getFirstName()+' '+$student->getLastName()
                ];
            }
            $output = [
                "data"            => $data,
                "recordsTotal"    => $total,
                "recordsFiltered" => $total
            ];
        }

        return $this->jsonResponse($output);
    }

    /**
     * @param $schoolClass
     * @param $student
     */
    protected function toggleStudent($schoolClass, $student)
    {
        if ($studentClass = $this->schoolClassStudentManager->findOneBySchoolClass($schoolClass,$student )) {
            $studentClass->delete();
        } else {
            $studentClass = new SchoolClassStudent();
            $studentClass->setSchoolClassId($schoolClass)->setStudentId($student)->save();
        }

        return;
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function updateSchoolClassStudentBySchoolClass($id)
    {
        $schoolClassStudent = [];
        if ($schoolClass = $this->schoolClassManager->findOne($id))
        {
            $schoolClassStudent = $this->schoolClassStudentManager->findAllBySchoolClass($id);
        }

        return $this->render(
            'PGSCoreDomainBundle:SchoolClassStudent:update_by_school_class.html.twig',
            [ 'schoolClassStudents' => $schoolClassStudent ]
        );
    }



}
