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
use PGS\CoreDomainBundle\Manager\FormulaManager;
use PGS\CoreDomainBundle\Model\Formula\Formula;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class FormulaController extends AbstractCoreBaseController
{
    /**
     * @var FormulaManager
     *
     * @Inject("pgs.core.manager.formula")
     */
    protected $formulaManager;

    /**
     * @Template("PGSCoreDomainBundle:Formula:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $formulas = $this->formulaManager->findAll();

        return [
            'model'    => 'Formula',
            'formulas' => $formulas
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Formula:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$formula = $this->formulaManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `formula` given');
        }

        return [
            'model'   => 'Formula',
            'formula' => $formula
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Formula:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $formula = new Formula();

        if (!$formula = $this->formulaManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_formula_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.formula'), $formula);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_formula_list'));
            }
        }

        return [
            'model'     => 'Formula',
            'formula'   => $formula,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Formula:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$formula = $this->formulaManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `formula` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.formula'), $formula);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_formula_list'));
            }
        }

        return [
            'model'     => 'Formula',
            'formula'   => $formula,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$formula = $this->formulaManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `formula` given');
        }

        $formula->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_formula_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Formula $formula*/
        $formula = $form->getData();
        $formula->save();
    }
}
