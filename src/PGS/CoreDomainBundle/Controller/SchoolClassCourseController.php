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
use PGS\CoreDomainBundle\Manager\SchoolClassCourseManager;
use PGS\CoreDomainBundle\Model\SchoolClassCourse\SchoolClassCourse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolClassCourseController extends AbstractCoreBaseController
{
    /**
     * @var SchoolClassCourseManager
     * @Inject("pgs.core.manager.school_class_course")
     */
    protected $schoolClassCourseManager;

    /**
     * @Template("PGSCoreDomainBundle:SchoolClassCourse:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolClassCourses  = $this->schoolClassCourseManager->findAll();


        return [
            'model'                => 'School Class Course',
            'schoolClassCourses'    => $schoolClassCourses,
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolClassCourse:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassCourse = $this->schoolClassCourseManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        return [
            'model'             => 'School Class Course',
            'schoolClassCourse' => $schoolClassCourse
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClassCourse:form.html.twig")
     */
    public function newAction(Request $request)
    {
//        $organizationId = $request->get('organization', null);
//
//        if ($organizationId === null) {
//            if (!$organization = $this->getActivePreference()->getOrganizationPreference()) {
//                if ($this->isGranted('ROLE_ADMIN')) {
//                    $organization = $this->getOrganizationManager()->findOne($organizationId);
//                }
//            }
//            $organizationId = $organization->getId();
//        }
//
//        if (!$organization = $this->getOrganizationManager()->findOne($organizationId)) {
//            throw $this->createNotFoundException('Invalid `organization` given');
//        }

        $schoolClassCourse = new SchoolClassCourse();

        if (!$schoolClassCourse = $this->schoolClassCourseManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
        }

//        $schoolClassCourse->setOrganizationId($organizationId);

        $form = $this->createForm($this->get('pgs.core.form.type.school_class_course'), $schoolClassCourse);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
            }
        }

        return [
            'model'  => 'School Class Course',
            'schoolClassCourse' => $schoolClassCourse,
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClassCourse:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassCourse = $this->schoolClassCourseManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class corse` given');
        }
//
//        if (!$this->schoolManager->canEdit($schoolClassCourse)) {
//            return $this->createAccessDeniedException('Unauthorized access');
//        }

        $form = $this->createForm($this->get('pgs.core.form.type.schoolClass'), $schoolClassCourse);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
            }
        }

        return [
            'model'     => 'School Class Course',
            'schoolClassCourse'    => $schoolClassCourse,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClassCourse = $this->schoolClassCourseManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school class course` given');
        }

        if ($this->schoolClassCourseManager->canDelete($schoolClassCourse)) {
            $schoolClassCourse->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolClassCourse $schoolClassCourse*/
        $schoolClassCourse = $form->getData();
        $schoolClassCourse->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolClassCourse:list_by_status.html.twig")
     * @param int $status
     *
     * @return Response
     */
    public function viewSchoolClassCourseByStatusAction($status)
    {
        $schoolClassCourses = [];
        $schoolClassCourses = $this->schoolClassCourseManager->findByStatus($status);

        return [ 'schoolClassCourses' => $schoolClassCourses ];
    }

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
            return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
        }

        if (!$schoolClassCourse= $this->schoolClassCourseManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        $this->schoolClassCourseManager->moveSchoolClassCourse($schoolClassCourse, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_course_list'));
    }

}
