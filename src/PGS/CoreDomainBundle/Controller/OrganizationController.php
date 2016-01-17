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
use PGS\CoreDomainBundle\Manager\OrganizationManager;
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\Organization\OrganizationI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class OrganizationController extends AbstractCoreBaseController
{
    /**
     * @var OrganizationManager
     * @Inject("pgs.core.manager.organization")
     */
    protected $organizationManager;

    /**
     * @Template("PGSCoreDomainBundle:Organization:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query = $this->organizationManager->findAll();

        $organizations = $this->get('knp_paginator');
        $organizations = $organizations->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        $statuses      = $this->organizationManager->getStatuses();
        $active        = $request->get('active','All');

        return [
            'model'         => 'Organization',
            'organizations' => $organizations,
            'statuses'      => $statuses,
            'active'        => $active
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Organization:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$organization = $this->organizationManager->findOneById($id)) {
            throw $this->createNotFoundException('Invalid `organization` given');
        }

        return [
            'model'        => 'Organization',
            'organization' => $organization
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     *
     * @Template("PGSCoreDomainBundle:Organization:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $organization = new Organization();

        if (!$organization = $this->organizationManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $organizationI18n = new OrganizationI18n();
            $organizationI18n->setLocale($locale);

            $organization->addOrganizationI18n($organizationI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.organization'), $organization);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
            }
        }

        return [
            'model'        => 'Organization',
            'organization' => $organization,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_PRINCIPAL') or hasRole('ROLE_SCHOOL')")
     *
     * @Template("PGSCoreDomainBundle:Organization:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$organization = $this->organizationManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `organization` given');
        }

        if (!$this->organizationManager->canEdit($organization)) {
            return $this->createAccessDeniedException('Unauthorized access');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.organization'), $organization);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
            }
        }

        return [
            'model'        => 'Organization',
            'organization' => $organization,
            'form'         => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$organization = $this->organizationManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `organization` given');
        }

        if (!$this->organizationManager->canDelete($organization)) {
            return $this->createAccessDeniedException('Unauthorized access');
        }

        $organization->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Organization $organization*/
        $organization = $form->getData();
        $organization->save();

        if ($organization->getLogo() !== null ) {
            if (!file_exists('uploads/organization/' . $organization->getId() . '/' . $organization->getLogo())) {
                if (!file_exists('uploads/organization/' . $organization->getId())) {
                    mkdir('uploads/organization/' . $organization->getId(), 0777, true);
                }
                rename(
                    'uploads/organization/temp/' . $organization->getLogo(),
                    'uploads/organization/' . $organization->getId() . '/' . $organization->getLogo()
                );
            }
        }

        $organization->save();
    }

    /**
     * @Template("PGSCoreDomainBundle:Organization:list_by_status.html.twig")
     * @param Request $request
     *
     * @return Response
     */
    public function viewOrganizationByStatusAction(Request $request)
    {
        $status = $request->get('status', 'All');

        $organizations = [];
        $organizations = $this->organizationManager->findByStatus($status);

        return [ 'organizations' => $organizations ];
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
            return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
        }

        if (!$organization = $this->organizationManager->findOneById($id)) {
            throw $this->createNotFoundException('Invalid `organization` given');
        }

        if (!$this->organizationManager->canEdit($organization)) {
            return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
        }

        $this->organizationManager->moveOrganization($organization, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_organization_list'));
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
                if (!file_exists('uploads/organization/temp')) {
                    mkdir('uploads/organization/temp', 0777, true);
                }
                $file_path = '/uploads/organization/temp/';
            } else {
                $file_path = '/uploads/organization/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];

            $target = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result = array('path' => $file_path, 'filename' => $name, 'inputId' => 'organization_logo');

            $result = json_encode($result);

            return new JsonResponse($result);
        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }
}
