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
use PGS\CoreDomainBundle\Manager\SchooltermManager;
use PGS\CoreDomainBundle\Manager\SchoolYearManager;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolTermController extends AbstractCoreBaseController
{
    /**
     * @var SchoolTermManager
     * @Inject("pgs.core.manager.school_term")
     */
    protected $schoolTermManager;

    /**
     * @var SchoolManager
     * @Inject("pgs.core.manager.school")
     */
    protected $schoolManager;


    /**
     * @Template("PGSCoreDomainBundle:SchoolTerm:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $schoolTerms = $this->schoolTermManager->findAll();
        $active      = $request->get('active','All');

        return [
            'model'       => 'School Term',
            'schoolTerms' => $schoolTerms,
            'active'      => $active
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:SchoolTerm:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTerm = $this->schoolTermManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `school term` given');
        }

        return [
            'model'      => 'School Term',
            'schoolTerm' => $schoolTerm
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolTerm:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $schoolTerm = new SchoolTerm();

        if (!$schoolTerm = $this->schoolTermManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
        }


        $form = $this->createForm($this->get('pgs.core.form.type.school_term'), $schoolTerm);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
            }
        }

        return [
            'model'      => 'School Term',
            'schoolTerm' => $schoolTerm,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     *
     * @Template("PGSCoreDomainBundle:SchoolTerm:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTerm = $this->schoolTermManager->canEdit($id)) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.school_term'), $schoolTerm);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
            }
        }

        return [
            'model'      => 'School Term',
            'schoolTerm' => $schoolTerm,
            'form'       => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_SCHOOL') or hasRole('ROLE_PRINCIPAL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$schoolTerm = $this->schoolTermManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `school term` givern');
        }

        $schoolTerm->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var SchoolTerm $schoolTerm*/
        $schoolTerm = $form->getData();
        $schoolTerm->save();

        // if this school term is set to active, make sure that other school term in the school
        // are non-active
        if ($schoolTerm->getActive()) {
            $this->schoolTermManager->setActive($schoolTerm);
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

        if (!$schoolTerm = $this->schoolTermManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school term` given');
        }

        $this->schoolTermManager->setActive($schoolTerm);

        return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
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
            return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
        }

        if (!$schoolTerm = $this->schoolTermManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `school term` given');
        }

        if (!$this->schoolTermManager->canEdit($schoolTerm)) {
            return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
        }

        $this->schoolTermManager->moveSchoolTerm($schoolTerm, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_school_term_list'));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function updateSchoolTermBySchoolAction($id)
    {
        $schoolTerms = [];
        if ($school = $this->schoolManager->findOne($id))
        {
            $schoolTerms = $this->schoolTermManager->findBySchool($id);
        }

        return $this->render(
            'PGSCoreDomainBundle:SchoolTerm:update_by_school.html.twig',
            [ 'schoolTerms' => $schoolTerms ]
        );
    }
}
