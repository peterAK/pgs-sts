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
use PGS\CoreDomainBundle\Manager\ParentStudentManager;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentStudentController extends AbstractCoreBaseController
{
    /**
     * @var ParentStudentManager
     *
     * @Inject("pgs.core.manager.parent_student")
     */
    protected $parentStudentManager;

    /**
     * @Template("PGSCoreDomainBundle:ParentStudent:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $parentStudents = $this->parentStudentManager->findAll();

        return [
            'model'            => 'Parent Student',
            'parentStudents'   => $parentStudents
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:ParentStudent:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$parentStudent = $this->parentStudentManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `parent student` given');
        }

        return [
            'model'         => 'Parent Student',
            'parentStudent' => $parentStudent
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:ParentStudent:form.html.twig")
     */
    public function newAction(Request $request)
    {
//        $student = new Student();
//
//        if (!student = $this->conditionManager->canAdd()) {
//            return new RedirectResponse($this->generateUrl('pgs_core_parent_student_list'));
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
//                return new RedirectResponse($this->generateUrl('pgs_core_parent_student_list'));
//            }
//        }
//
//        return [
//            'model'         => 'Parent Student',
//            'parentStudent' => $parentStudent,
//            'form'          => $form->createView()
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

        if (!$parentStudent = $this->parentStudentManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `parent student` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.student'), $parentStudent);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_parent_student_list'));
            }
        }

        return [
            'model'     => 'Parent Student',
            'parentStudent'   => $parentStudent,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$parentStudent = $this->parentStudentManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `Parent Student` given');
        }

        $parentStudent->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_parent_student_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var ParentStudent $parentStudent*/
        $parentStudent = $form->getData();
        $parentStudent->save();
    }
}
