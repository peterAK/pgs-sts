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
use PGS\CoreDomainBundle\Manager\ConditionManager;
use PGS\CoreDomainBundle\Model\Condition\Condition;
use PGS\CoreDomainBundle\Model\Condition\ConditionI18n;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class ConditionController extends AbstractCoreBaseController
{
    /**
     * @var ConditionManager
     *
     * @Inject("pgs.core.manager.condition")
     */
    protected $conditionManager;

    /**
     * @Template("PGSCoreDomainBundle:Condition:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $conditions = $this->conditionManager->findAll();

        return [
            'model'      => 'Condition',
            'conditions' => $conditions
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Condition:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$condition = $this->conditionManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `condition` given');
        }

        return [
            'model'     => 'Condition',
            'condition' => $condition
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     * @Template("PGSCoreDomainBundle:Condition:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $condition = new Condition();

        if (!$condition = $this->conditionManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_condition_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $conditionI18n = new ConditionI18n();
            $conditionI18n->setLocale($locale);

            $condition->addConditionI18n($conditionI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.condition'), $condition);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_condition_list'));
            }
        }

        return [
            'model'     => 'Condition',
            'condition' => $condition,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Condition:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$condition = $this->conditionManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `condition` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.condition'), $condition);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_condition_list'));
            }
        }

        return [
            'model'     => 'Condition',
            'condition' => $condition,
            'form'      => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$condition = $this->conditionManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `condition` given');
        }

        $condition->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_condition_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Condition $condition*/
        $condition = $form->getData();
        $condition->save();
    }
}
