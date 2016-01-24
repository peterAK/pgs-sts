<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 6/17/2015.
 */

namespace PGS\CoreDomainBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use PGS\CoreDomainBundle\Manager\UserProfileManager;
use PGS\CoreDomainBundle\Manager\UserManager;
use PGS\CoreDomainBundle\Model\UserProfile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserProfileController extends AbstractBaseController
{
    /**
     * @var UserProfileManager
     *
     * @Inject("pgs.core.manager.user_profile")
     */
    protected $userProfileManager;

    /**
     * @var UserManager
     *
     * @Inject("pgs.core.manager.user")
     */
    protected $userManager;

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Application:list.html.twig")
     */
    public function listAction(Request $request)
    {

        // if use paginate
        $query       = $this->userProfileManager->findAll();
        $userProfile = $this->get('knp_paginator');
        $userProfile = $userProfile->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            3
        );

//        $application = $this->applicationManager->findAll();

        return [
            'model'      => 'User',
            'users'       => $userProfile,

        ];
    }


    /**
     * @Template("PGSCoreDomainBundle:UserProfile:new.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$user = $this->userManager->findOneById($id)) {
            throw $this->createNotFoundException('Invalid `user` given');
        }

        if (!$userProfile = $this->userProfileManager->findOneById($id)) {
            $userProfile = new UserProfile();
            $userProfile->setId($id);

        }


        $form = $this->createForm($this->get('pgs.core.form.type.user_profile'), $userProfile);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('homepage'));
            }
        }

        return [
            'model'       => 'User Profile',
            'userProfile' => $userProfile,
            'form'        => $form->createView()
        ];
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        /** @var UserProfile $userProfile*/
        $medical = $form->getData();
        $medical->save();
    }
}
