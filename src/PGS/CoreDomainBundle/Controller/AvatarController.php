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
use PGS\CoreDomainBundle\Manager\AvatarManager;
use PGS\CoreDomainBundle\Model\Avatar\Avatar;
use PGS\CoreDomainBundle\Model\Avatar\AvatarI18n;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class AvatarController extends AbstractCoreBaseController
{

    /**
     * @var AvatarManager
     * @Inject("pgs.core.manager.avatar")
     */
    protected $avatarManager;

    /**
     * @Template("PGSCoreDomainBundle:Avatar:list.html.twig")
     */
    public function listAction(Request $request)
    {

        $query   = $this->avatarManager->findAll();
        $user    = $this->getUser()->getUserProfile();
        $avatars = $this->get('knp_paginator');
        $avatars = $avatars->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );
        return [
            'model'   => 'Avatar',
            'avatars' => $avatars,
            'user'    => $user
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Avatar:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');
        $user    = $this->getUser()->getUserProfile();

        if (!$avatar = $this->avatarManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `avatar` given');
        }

        return [
            'model'        => 'Avatar',
            'avatar'       => $avatar,
            'user'    => $user
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:Avatar:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $user    = $this->getUser()->getUserProfile();
        $avatar = new Avatar();

        if (!$avatar = $this->avatarManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
        }

        $locales = $this->getLocales();

        foreach ($locales as $locale) {
            $avatarI18n = new AvatarI18n();
            $avatarI18n->setLocale($locale);
            $avatar->addAvatarI18n($avatarI18n);
        }

        $form = $this->createForm($this->get('pgs.core.form.type.avatar'), $avatar);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
            }
        }

        return [
            'model'        => 'Avatar',
            'avatar'       => $avatar,
            'form'         => $form->createView(),
            'user'    => $user
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:avatar:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $user    = $this->getUser()->getUserProfile();

        if (!$avatar = $this->avatarManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `avatar` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.avatar'), $avatar);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
            }
        }

        return [
            'model'        => 'Avatar',
            'avatar'       => $avatar,
            'form'         => $form->createView(),
            'user'    => $user
        ];
    }

    /**
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$avatar = $this->avatarManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `avatar` given');
        }

        $avatar->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Avatar $avatar*/
        $avatar = $form->getData();
        $avatar->save();

        if (!file_exists('uploads/avatar/' . $avatar->getId() . '/' . $avatar->getPicFile())) {
            if (!file_exists('uploads/avatar/' . $avatar->getId() )) {
                mkdir('uploads/avatar/' . $avatar->getId(), 0777, true);
            }
            rename(
                'uploads/avatar/temp/' . $avatar->getPicFile(),
                'uploads/avatar/' . $avatar->getId() . '/' . $avatar->getPicFile()
            );
        }

        $avatar->save();
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
            return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
        }

        if (!$avatar = $this->avatarManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `avatar` given');
        }

        $this->avatarManager->moveAvatar($avatar, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_avatar_list'));
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
                if (!file_exists('uploads/avatar/temp')) {
                    mkdir('uploads/avatar/temp', 0777, true);
                }
                $file_path = '/uploads/avatar/temp/';
            } else {
                $file_path = '/uploads/avatar/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];

            $target = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result = array('path' => $file_path, 'filename' => $name, 'inputId' => 'avatar_picFile');

            $result = json_encode($result);

            return new JsonResponse($result);
        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }


}
