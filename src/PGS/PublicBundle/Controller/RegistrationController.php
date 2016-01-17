<?php

/**
 * This file is part of the PGS/PublicBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\PublicBundle\Controller;

use PGS\CoreDomainBundle\Model\User;
use PGS\CoreDomainBundle\Model\UserProfile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegistrationController extends Controller
{
    /**
     * @Template("PGSCoreDomainBundle:Registration:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $role = $request->get('role', 'student');
        $user = new User();

        $form = $this->createForm($this->get('pgs.core.form.type.registration.user'), $user, [ 'role' => $role ]);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $id = $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_user_profile_edit',['id' => $id]));
            }
        }

        return [
            'model' => 'User',
            'user'  => $user,
            'form'  => $form->createView()
        ];
    }

    /**
     * @param Form $form
     * @return int
     */
    private function processForm(Form $form)
    {
        $fosuserManager = $this->container->get('fos_user.user_manager');
        $userManager    = $this->container->get('pgs.core.manager.user');

        /** @var User $user */
        $user = $form->getData();

        if ($user->isNew()) {
            $userRole = $userManager->convertTypeToRole($form["role"]->getData());
            $user->setRoles($userRole);
            $user->setEnabled(false);
        }

        $fosuserManager->updateUser($user);
       return $user->getId();
    }
}
