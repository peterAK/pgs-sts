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
use PGS\CoreDomainBundle\Manager\SchoolGradeLevelManager;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\School\School;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class SchoolGradeLevelController extends AbstractCoreBaseController
{
    /**
     * @var SchoolGradeLevelManager
     * @Inject("pgs.core.manager.school_grade_level")
     */
    protected $schoolGradeLevelManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     * @Template("PGSCoreDomainBundle:SchoolGradeLevel:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolGradeLevels = $this->schoolGradeLevelManager->findAll()->find();

        return [
            'model'             => 'School Grade Level',
            'schoolGradeLevels' => $schoolGradeLevels
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     * @Template("PGSCoreDomainBundle:SchoolGradeLevel:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolGradeLevels = $this->schoolGradeLevelManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `school grade level` given');
        }
        return [
            'model'             => 'School  Grade Level',
            'schoolGradeLevels' => $schoolGradeLevels
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolGradeLevel:form.html.twig")
     */
    public function newAction(Request $request)
    {
//        $school = $this->getActivePreference()->getSchoolPreference()->getId();
        $schoolGradeLevel = new SchoolGradeLevel();
//        $schoolGradeLevel->setSchoolId($school);

        if (!$schoolGradeLevel= $this->schoolGradeLevelManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_grade_level'), $schoolGradeLevel);

        if ($request->getMethod() == "POST") {
            $form->submit($request);
            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
            }
        }

        return [
            'model'             => 'School Grade Level',
            'schoolGradeLevel'  => $schoolGradeLevel,
            'form'              => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolGradeLevel:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolGradeLevels = $this->schoolGradeLevelManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `school grade level` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_grade_level'), $schoolGradeLevels);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
            }
        }

        return [
            'model'             => 'School Grade Level',
            'schoolGradeLevels' => $schoolGradeLevels,
            'form'              => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolGradeLevels = $this->schoolGradeLevelManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school grade level` given');
        }

        $schoolGradeLevels->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolGradeLevel $schoolGradeLevel*/
        $schoolGradeLevel = $form->getData();
        $schoolGradeLevel->save();

        // if this school year is set to active, make sure that other school year in the school
        // are non-active
//        if ($schoolGradeLevels->getActive()) {
//            $this->schoolGradeLevelManager->setActive($schoolGradeLevels);
//        }
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

        if (!$schoolGradeLevels = $this->schoolGradeLevelManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        $this->schoolGradeLevelManager->setActive($schoolGradeLevels);

        return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
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
            return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
        }

        if (!$schoolGradeLevels = $this->schoolGradeLevelManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school year` given');
        }

        $this->schoolGradeLevelManager->moveSchoolYear($schoolGradeLevels, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_grade_level_list'));
    }
}

