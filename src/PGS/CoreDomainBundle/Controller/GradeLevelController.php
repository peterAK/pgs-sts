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
use PGS\CoreDomainBundle\Manager\GradeLevelManager;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class GradeLevelController extends AbstractCoreBaseController
{
    /**
     * @var GradeLevelManager
     *
     * @Inject("pgs.core.manager.grade_level")
     */
    protected $gradeLevelManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:GradeLevel:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $gradeLevels = $this->gradeLevelManager->findAll();

        return [
            'model'       => 'Grade Level',
            'gradeLevels' => $gradeLevels
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSAdminBundle:GradeLevel:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$gradeLevel = $this->gradeLevelManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `grade level` given');
        }

        return [
            'model'      => 'Grade Level',
            'gradeLevel' => $gradeLevel
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:GradeLevel:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $gradeLevel = new GradeLevel();

        if(!$gradeLevel=$this->gradeLevelManager->canAdd()){
            return new RedirectResponse($this->generateUrl('pgs_core_grade_level_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.grade_level'), $gradeLevel);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_grade_level_list'));
            }
        }

        return [
            'model'      => 'Grade Level',
            'gradeLevel' => $gradeLevel,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:GradeLevel:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$gradeLevel = $this->gradeLevelManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `grade level` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.grade_level'), $gradeLevel);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_grade_level_list'));
            }
        }

        return [
            'model'      => 'GradeLevel',
            'gradeLevel' => $gradeLevel,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$gradeLevel = $this->gradeLevelManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `grade level` given');
        }

        $gradeLevel->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_grade_level_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var GradeLevel $gradeLevel*/
        $gradeLevel = $form->getData();

        if ( !$gradeLevelExist = $this->gradeLevelManager->checkExist($gradeLevel->getLevelId(),$gradeLevel->getGradeId()) ) {
            $gradeLevel->save();
        }
    }
}
