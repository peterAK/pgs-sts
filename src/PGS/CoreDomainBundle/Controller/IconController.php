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
use PGS\CoreDomainBundle\Manager\IconManager;
use PGS\CoreDomainBundle\Model\Icon\Icon;
use PGS\CoreDomainBundle\Model\Icon\IconI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class IconController extends AbstractBaseController
{
    /**
     * @var IconManager
     * @Inject("pgs.core.manager.icon")
     */
    protected $iconManager;

    /**
     * @Template("PGSCoreDomainBundle:Icon:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query = $this->iconManager->findAll();

        $icons = $this->get('knp_paginator');
        $icons = $icons->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        $statuses      = $this->iconManager->getStatuses();
        $active        = $request->get('active','All');
        return [
            'model'         => 'Icon',
            'icons'         => $icons,
            'statuses'      => $statuses,
            'active'        => $active
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Icon:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $icon = new Icon();

        if (!$icon = $this->iconManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $iconI18n = new IconI18n();
            $iconI18n->setLocale($locale);

            $icon->addIconI18n($iconI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.icon'), $icon);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
            }
        }

        return [
            'model'        => 'Icon',
            'icon'         => $icon,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:icon:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$icon = $this->iconManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `icon` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.icon'), $icon);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
            }
        }

        return [
            'model'        => 'Icon',
            'icon'         => $icon,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$icon = $this->iconManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `icon` given');
        }

        $icon->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Icon $icon*/
        $icon = $form->getData();
        $icon->save();

        if (!file_exists('uploads/icon/' . $icon->getId() . '/' . $icon->getIconFile())) {
            if (!file_exists('uploads/icon/' . $icon->getId() )) {
                mkdir('uploads/icon/' . $icon->getId(), 0777, true);
            }
            rename(
                'uploads/icon/temp/' . $icon->getIconFile(),
                'uploads/icon/' . $icon->getId() . '/' . $icon->getIconFile()
            );
        }

        $icon->save();
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Icon:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$icon = $this->iconManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `icon` given');
        }

        return [
            'model'        => 'Icon',
            'icon'         => $icon
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Icon:list_by_status.html.twig")
     * @param Request $request
     *
     * @return Response
     */
    public function viewIconByStatusAction(Request $request)
    {
        $status = $request->get('status', 'All');

        $icons = [];
        $icons = $this->iconManager->findByStatus($status);

        return [ 'icons' => $icons ];
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
            return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
        }

        if (!$icon = $this->iconManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `icon` given');
        }

        $this->iconManager->moveIcon($icon, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_icon_list'));
    }

    /**
     * @param null $id
     *
     * @return JsonResponse
     */
    public function uploadAction($id = null)
    {
        try {
            if ($id == null) {
                if (!file_exists('uploads/icon/temp')) {
                    mkdir('uploads/icon/temp', 0777, true);
                }
                $file_path = '/uploads/icon/temp/';
            } else {
                $file_path = '/uploads/icon/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];

            $target = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result = array('path' => $file_path, 'filename' => $name, 'inputId' => 'icon_iconFile');

            $result = json_encode($result);

            return new JsonResponse($result);
        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

}
