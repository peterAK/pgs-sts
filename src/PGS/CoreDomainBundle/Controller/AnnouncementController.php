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
use PGS\CoreDomainBundle\Manager\AnnouncementManager;
use PGS\CoreDomainBundle\Model\Announcement\Announcement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class AnnouncementController extends AbstractCoreBaseController
{

    /**
     * @var AnnouncementManager
     * @Inject("pgs.core.manager.announcement")
     */
    protected $announcementManager;

    /**
     * @Template("PGSCoreDomainBundle:Announcement:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query   = $this->announcementManager->findSome();
        $user    = $this->getUser()->getUserProfile();
        $announcements = $this->get('knp_paginator');
        $announcements = $announcements->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );
        return [
            'model'   => 'Announcement',
            'announcements' => $announcements,
            'user'    => $user
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Announcement:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');
        $user    = $this->getUser()->getUserProfile();

        if (!$announcement = $this->announcementManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `Announcement` given');
        }

        return [
            'model'        => 'Announcement',
            'announcement'       => $announcement,
            'user'    => $user
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:Announcement:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $user    = $this->getUser()->getUserProfile();
        $announcement = new Announcement();

        if (!$announcement = $this->announcementManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_announcement_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.announcement'), $announcement);

        if ($request->getMethod() == "POST") {
            $form->submit($request);


            if ($form->isValid()) {
                $postedBy=$this->getActivePreference()->getCurrentUserProfile()->getId();
                $recipient=$_POST['recipient'];
                $announcement->setRecipient($recipient);
                $announcement->setPostedBy($postedBy);

                $this->processForm($form);


                return new RedirectResponse($this->generateUrl('pgs_core_announcement_list'));
            }
        }
        return [
            'model'        => 'Announcement',
            'announcement'       => $announcement,
            'form'         => $form->createView(),
            'user'    => $user
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:announcement:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $user    = $this->getUser()->getUserProfile();

        if (!$announcement = $this->announcementManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `Announcement` given');
        }

        $form = $this->createForm($this->get('pgs.core.form.type.announcement'), $announcement);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_announcement_list'));
            }
        }

        return [
            'model'        => 'Announcement',
            'announcement'       => $announcement,
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

        if (!$announcement = $this->announcementManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `Announcement` given');
        }

        $announcement->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_announcement_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Announcement $announcement*/
        $announcement = $form->getData();
        $announcement->save();

        if ($announcement->getFile() !== null ) {
            if (!file_exists('uploads/announcement/' . $announcement->getId() . '/' . $announcement->getFile())) {
                if (!file_exists('uploads/announcement/' . $announcement->getId() )) {
                    mkdir('uploads/announcement/' . $announcement->getId(), 0777, true);
                }
                rename(
                    'uploads/announcement/temp/' . $announcement->getFile(),
                    'uploads/announcement/' . $announcement->getId() . '/' . $announcement->getFile()
                );
            }
        }
        $announcement->save();
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
                if (!file_exists('uploads/announcement/temp')) {
                    mkdir('uploads/announcement/temp', 0777, true);
                }
                $file_path = '/uploads/announcement/temp/';
            } else {
                $file_path = '/uploads/announcement/' . $id . '/';
            }

            $path_parts = pathinfo($_FILES["images"]["name"][0]);
            $name       = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension'];

            $target = $_SERVER['DOCUMENT_ROOT'] . $file_path;
            move_uploaded_file($_FILES["images"]["tmp_name"][0], $target . $name);

            $result = array('path' => $file_path, 'filename' => $name, 'inputId' => 'announcement_file');

            $result = json_encode($result);

            return new JsonResponse($result);
        } catch (Exception $e) {
            return new JsonResponse('failed');
        }
    }

}
