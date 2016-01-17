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
use PGS\CoreDomainBundle\Manager\PageManager;
use PGS\CoreDomainBundle\Model\Page;
use PGS\CoreDomainBundle\Model\PageI18n;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;

class PageController extends AbstractCoreBaseController
{
    /**
     * @var PageManager
     *
     * @Inject("pgs.core.manager.page")
     */
    protected $pageManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Page:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $pages    = $this->pageManager->findAll();
        $statuses = $this->pageManager->getStatuses();
        $active   = $request->get('active','All');


        return [
            'model'    => 'Page',
            'pages'    => $pages,
            'statuses' => $statuses,
            'active'   => $active
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Page:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$page = $this->pageManager->findOne($id)) {
            return $this->createNotFoundException('Invalid `page` given');
        }

        if ($this->pageManager->canView($page))
        {
            return $this->createAccessDeniedException('Unauthorized access');
        }

        return [
            'model' => 'Page',
            'page'  => $page
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Page:sidebar.html.twig")
     */
    public function sidebarAction(Request $request)
    {
        $id = $request->get('id');

        if (!$page = $this->pageManager->findOne($id)) {

            return $this->createNotFoundException('Invalid `page` given');
        }

        return [
            'model' => 'Page',
            'page'  => $page
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL) or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Page:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $page = new Page();

        if (!$help = $this->pageManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_page_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $pageI18n = new PageI18n();
            $pageI18n->setLocale($locale);

            $page->addPageI18n($pageI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.page'), $page);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_page_list'));
            }
        }

        return [
            'model' => 'Page',
            'page'  => $page,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$page = $this->pageManager->canDelete($id, 'delete')) {
            throw $this->createNotFoundException('Invalid `page` given');
        }

        if ($this->pageManager->canDelete($page))
        {
            $page->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_page_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Page $page*/
        $page = $form->getData();
        $page->save();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function viewPageByStatusAction(Request $request)
    {
        $status = $request->get('status', 'All');

        $pages = [];
        $pages = $this->pageManager->findByStatus($status);

        return $this->render(
            'PGSCoreDomainBundle:Page:list_by_status.html.twig',
            [ 'pages' => $pages ]
        );
    }
}
