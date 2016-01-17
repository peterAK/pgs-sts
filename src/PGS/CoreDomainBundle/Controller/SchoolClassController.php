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
use PGS\CoreDomainBundle\Manager\SchoolClassManager;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClass;
use PGS\CoreDomainBundle\Model\SchoolClass\SchoolClassI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolClassController extends AbstractCoreBaseController
{
    /**
     * @var SchoolClassManager
     * @Inject("pgs.core.manager.school_class")
     */
    protected $schoolClassManager;

    /**
     * @Template("PGSCoreDomainBundle:SchoolClass:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolClasses  = $this->schoolClassManager->findAll();

        return [
            'model'          => 'School Class',
            'schoolClasses'  => $schoolClasses,
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolClass:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClass = $this->schoolClassManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        return [
            'model'       => 'School Class',
            'schoolClass' => $schoolClass
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClass:form.html.twig")
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

        $schoolClass = new SchoolClass();

        if (!$schoolClass = $this->schoolClassManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
        }

//        $schoolClass->setOrganizationId($organizationId);

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $schoolClassI18n = new SchoolClassI18n();
            $schoolClassI18n->setLocale($locale);

            $schoolClass->addSchoolClassI18n($schoolClassI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_class'), $schoolClass);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
            }
        }

        return [
            'model'  => 'School Class',
            'schoolClass' => $schoolClass,
            'form'   => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:SchoolClass:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClass = $this->schoolClassManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        if (!$this->schoolManager->canEdit($schoolClass)) {
            return $this->createAccessDeniedException('Unauthorized access');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.schoolClass'), $schoolClass);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
            }
        }

        return [
            'model'     => 'School Class',
            'schoolClass'    => $schoolClass,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolClass = $this->schoolClassManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        if ($this->schoolClassManager->canDelete($schoolClass)) {
            $schoolClass->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolClass $schoolClass*/
        $schoolClass = $form->getData();
        $schoolClass->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolClass:list_by_status.html.twig")
     * @param int $status
     *
     * @return Response
     */
    public function viewSchoolClassByStatusAction($status)
    {
        $schoolClasses = [];
        $schoolClasses = $this->schoolClassManager->findByStatus($status);

        return [ 'schoolClasses' => $schoolClasses ];
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
            return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
        }

        if (!$schoolClass= $this->schoolClassManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school class` given');
        }

        $this->schoolClassManager->moveSchoolClass($schoolClass, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_class_list'));
    }

}
