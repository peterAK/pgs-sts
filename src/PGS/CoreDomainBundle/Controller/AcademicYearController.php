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
use PGS\CoreDomainBundle\Manager\AcademicYearManager;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class AcademicYearController extends AbstractCoreBaseController
{
    /**
     * @var AcademicYearManager
     *
     * @Inject("pgs.core.manager.academic_year")
     */
    protected $academicYearManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:AcademicYear:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $academicYears = $this->academicYearManager->findAll();

        return [
            'model'         => 'Academic Year',
            'academicYears' => $academicYears
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:AcademicYear:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$academicYear = $this->academicYearManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `academic year` given');
        }

        return [
            'title'        => 'View Academic Year',
            'model'        => 'Academic Year',
            'academicYear' => $academicYear
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:AcademicYear:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $academicYear = new AcademicYear();

        if(!$academicYear=$this->academicYearManager->canAdd()){
            return new RedirectResponse($this->generateUrl('pgs_core_academic_year_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.academic_year'), $academicYear);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_academic_year_list'));
            }
        }

        return [
            'title'        => 'New Academic Year',
            'model'        => 'Academic Year',
            'academicYear' => $academicYear,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:AcademicYear:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$academicYear = $this->academicYearManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `academic year` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.academic_year'), $academicYear);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_academic_year_list'));
            }
        }

        return [
            'title'        => 'Edit Academic Year',
            'model'        => 'Academic Year',
            'academicYear' => $academicYear,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$academicYear = $this->academicYearManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `academic year` given');
        }

        $academicYear->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_academic_year_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var AcademicYear $academicYear*/
        $academicYear = $form->getData();
        $academicYear->save();
    }

    public function activateAction(Request $request)
    {
        $id = $request->get('id');

        if (!$academicYear = $this->academicYearManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `academic year` given');
        }

        $this->academicYearManager->setActive($academicYear);

        return new RedirectResponse($this->generateUrl('pgs_core_academic_year_list'));
    }
}
