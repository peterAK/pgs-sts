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
use PGS\CoreDomainBundle\Controller\AbstractBaseController as CoreAbstractBaseController;
use PGS\CoreDomainBundle\Manager\SchoolYearManager;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolYearController extends AbstractCoreBaseController
{
    /**
     * @var SchoolYearManager
     * @Inject("pgs.core.manager.school_year")
     */
    protected $schoolYearManager;

    /**
     * @var SchoolManager
     * @Inject("pgs.core.manager.school")
     */
    protected $schoolManager;


    /**
     * @Template("PGSCoreDomainBundle:SchoolYear:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolYears = $this->schoolYearManager->findAll();

        return [
            'model'       => 'School Year',
            'schoolYears' => $schoolYears
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolYear:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolYear = $this->schoolYearManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        return [
            'model'      => 'School Year',
            'schoolYear' => $schoolYear
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolYear:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $schoolYear = new SchoolYear();

        if (!$schoolYear = $this->schoolYearManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $schoolYearI18n = new SchoolYearI18n();
            $schoolYearI18n->setLocale($locale);

            $schoolYear->addSchoolYearI18n($schoolYearI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_year'), $schoolYear);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
            }
        }

        return [
            'model'      => 'School Year',
            'schoolYear' => $schoolYear,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolYear:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolYear = $this->schoolYearManager->canEdit($id)) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_year'), $schoolYear);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
            }
        }

        return [
            'model'      => 'School Year',
            'schoolYear' => $schoolYear,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolYear = $this->schoolYearManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        $schoolYear->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolYear $schoolYear*/
        $schoolYear = $form->getData();
        $schoolYear->save();

        // if this school year is set to active, make sure that other school year in the school
        // are non-active
        if ($schoolYear->getActive()) {
            $this->schoolYearManager->setActive($schoolYear);
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function activateAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolYear = $this->schoolYearManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        $this->schoolYearManager->setActive($schoolYear);

        return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
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
        $id        = $request->get('id',null);
        $direction = $request->get('direction', null);

        if ($direction === null) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
        }

        if (!$schoolYear = $this->schoolYearManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        if (!$this->schoolYearManager->canEdit($schoolYear)) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
        }

        $this->schoolYearManager->moveSchoolYear($schoolYear, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_year_list'));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function updateSchoolYearBySchoolAction($id)
    {
        $schoolYears = [];
        if ($school = $this->schoolManager->findOne($id))
        {
            $schoolYears = $this->schoolYearManager->findBySchool($id);
        }

        return $this->render(
            'PGSCoreDomainBundle:SchoolYear:update_by_school.html.twig',
            [ 'schoolYears' => $schoolYears ]
        );
    }
}
