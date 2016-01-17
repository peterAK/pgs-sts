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
use PGS\CoreDomainBundle\Manager\BehaviorManager;
use PGS\CoreDomainBundle\Model\Behavior\Behavior;
use PGS\CoreDomainBundle\Model\Behavior\BehaviorI18n;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class BehaviorController extends AbstractCoreBaseController
{
    /**
     * @var BehaviorManager
     *
     * @Inject("pgs.core.manager.behavior")
     */
    protected $behaviorManager;

    /**
     * @Template("PGSCoreDomainBundle:Behavior:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query = $this->behaviorManager->findAll();
        $behaviors = $this->get('knp_paginator');
        $behaviors = $behaviors->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/, 10
        );
        return [
            'model'     => 'Behavior',
            'behaviors' => $behaviors
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSAdminBundle:Behavior:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$behavior = $this->behaviorManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `behavior` given');
        }

        return [
            'model'    => 'Behavior',
            'behavior' => $behavior
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Behavior:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $behavior = new Behavior();

        if (!$behavior = $this->behaviorManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_behavior_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $behaviorI18n = new BehaviorI18n();
            $behaviorI18n->setLocale($locale);

            $behavior->addBehaviorI18n($behaviorI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.behavior'), $behavior);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_behavior_list'));
            }
        }

        return [
            'model'    => 'Behavior',
            'behavior' => $behavior,
            'form'     => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Behavior:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$behavior = $this->behaviorManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `behavior` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.behavior'), $behavior);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);
                return new RedirectResponse($this->generateUrl('pgs_core_behavior_list'));
            }
        }

        return [
            'model'    => 'Behavior',
            'behavior' => $behavior,
            'form'     => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$behavior = $this->behaviorManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `behavior` given');
        }

        $behavior->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_behavior_list'));
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var Behavior $behavior*/
        $behavior = $form->getData();
        /** @var Behavior $behavior*/
        $behavior = $form->getData();
            if ($behavior->isNew()) {
                $behavior->setUserId($this->getUser()->getId());
            }
        $behavior->save();
    }

}
