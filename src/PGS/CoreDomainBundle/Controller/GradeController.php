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
use PGS\CoreDomainBundle\Manager\GradeManager;
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Grade\GradeI18n;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class GradeController extends AbstractCoreBaseController
{
    /**
     * @var GradeManager
     *
     * @Inject("pgs.core.manager.grade")
     */
    protected $gradeManager;

    /**
     * @Template("PGSCoreDomainBundle:Grade:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $grades = $this->gradeManager->findAll();

        return [
            'model'  => 'Grade',
            'grades' => $grades
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Grade:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$grade = $this->gradeManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `grade` given');
        }

        return [
            'model' => 'Grade',
            'grade' => $grade
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Grade:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $grade = new Grade();

        if (!$grade = $this->gradeManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_grades_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $gradeI18n = new GradeI18n();
            $gradeI18n->setLocale($locale);

            $grade->addGradeI18n($gradeI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.grade'), $grade);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_grades_list'));
            }
        }

        return [
            'model' => 'Grade',
            'grade' => $grade,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Grade:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$grade = $this->gradeManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `grade` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.grade'), $grade);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_grades_list'));
            }
        }

        return [
            'model' => 'Grade',
            'grade' => $grade,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$grade = $this->gradeManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `grade` given');
        }

        $grade->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_grades_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Grade $grade*/
        $grade = $form->getData();
        $grade->save();
    }
}
