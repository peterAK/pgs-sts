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
use PGS\CoreDomainBundle\Manager\StudentManager;
use PGS\CoreDomainBundle\Model\Student\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends AbstractCoreBaseController
{
    /**
     * @var StudentManager
     *
     * @Inject("pgs.core.manager.student")
     */
    protected $studentManager;

    /**
     * @Template("PGSCoreDomainBundle:Student:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $students = $this->studentManager->findAll();

        return [
            'model'      => 'Student',
            'students'   => $students
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Student:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$student = $this->studentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `student` given');
        }

        return [
            'model'     => 'Student',
            'student' => $student
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Student:form.html.twig")
     */
    public function newAction(Request $request)
    {
//        $student = new Student();
//
//        if (!student = $this->conditionManager->canAdd()) {
//            return new RedirectResponse($this->generateUrl('pgs_core_student_list'));
//        }
//
//        $form = $this->createForm($this->get('pgs.core.form.type.student'), $student);
//
//        if ($request->getMethod() == "POST") {
//            $form->submit($request);
//
//            if ($form->isValid()) {
//                $this->processForm($form);
//
//                return new RedirectResponse($this->generateUrl('pgs_core_student_list'));
//            }
//        }
//
//        return [
//            'model'     => 'Student',
//            'student'   => $student,
//            'form'      => $form->createView()
//        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Student:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$student = $this->studentManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `student` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.student'), $student);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_student_list'));
            }
        }

        return [
            'model'     => 'Student',
            'student'   => $student,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$student = $this->studentManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `Student` given');
        }

        $student->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_student_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Student $student*/
        $student = $form->getData();
        $student->save();
    }
}
