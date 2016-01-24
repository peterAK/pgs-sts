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
use PGS\CoreDomainBundle\Manager\HelpManager;
use PGS\CoreDomainBundle\Model\Help;
use PGS\CoreDomainBundle\Model\HelpI18n;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class HelpController extends AbstractBaseController
{
    /**
     * @var HelpManager
     *
     * @Inject("pgs.core.manager.help")
     */
    protected $helpManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Help:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query = $this->helpManager->findAll();

        $helps = $this->get('knp_paginator');
        $helps = $helps->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        return [
            'model'    => 'Help',
            'helps'    => $helps
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Help:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$help = $this->helpManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `help` given');
        }

        return [
            'title' => 'View Help',
            'model' => 'Help',
            'help'  => $help
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Help:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $key = $request->get('key', null);

        if (!$help = $this->helpManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_help_list'));
        }

        if ($key !== null) {
            $help->setKey($key);
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $helpI18n = new HelpI18n();
            $helpI18n->setLocale($locale);

            $help->addHelpI18n($helpI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.help'), $help);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_help_list'));
            }
        }

        return [
            'title' => 'New Help',
            'model' => 'Help',
            'help'  => $help,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Help:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$help = $this->helpManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `help` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.help') , $help);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_help_list'));
            }
        }

        return [
            'model' => 'Help',
            'help'  => $help,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        /* @var Help $help */
        if (!$help = $this->helpManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `help` given');
        }

        $help->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_help_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Help $help */
        $help = $form->getData();
        $help->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:Help:fetch.html.twig")
     */
    public function fetchAction(Request $request)
    {
        $key  = $request->get('key');
        $notFound = false;

        if (!$help = $this->helpManager->findOneByKey($key)) {
            $help =  $this->helpManager->findOneByKey('not.defined');
            $help->setTitle($key.': '.$help->getTitle());
            $notFound = true;
        }

        return [
            'help'     => $help,
            'key'      => $key,
            'notFound' => $notFound
        ];
    }
}
