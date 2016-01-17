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
use PGS\CoreDomainBundle\Manager\UserProfileManager;
use PGS\CoreDomainBundle\Manager\MessageManager;
use PGS\CoreDomainBundle\Model\Message\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class MessageController extends AbstractCoreBaseController
{

    /**
     * @var UserProfileManager
     * @Inject("pgs.core.manager.user_profile")
     */
    protected $userProfileManager;

    /**
     * @var MessageManager
     * @Inject("pgs.core.manager.message")
     */
    protected $messageManager;

    /**
     * @Template("PGSCoreDomainBundle:Message:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $messages = $this->messageManager->findSome();
        $heads    = $this->messageManager->findMessageHead();
        $user     = $this->getUser()->getUserProfile();
        $userId   = $this->getUser()->getUserProfile()->getId();

        return [
            'model'   => 'Message',
            'heads' => $heads,
            'messages' => $messages,
            'user'    => $user,
            'userId'    => $userId
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Message:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        ini_set('date.timezone', 'Asia/Bangkok');
        $id         = $request->get('id');
        $user       = $this->getUser()->getUserProfile();
        $userId     = $this->getUser()->getUserProfile()->getId();
        $firsts     = $this->messageManager->findById($id);
        $messages   = $this->messageManager->findByOriginalId($id);

        foreach($messages as $message){
            /** @var Message $message */
            if($message->getFromId()!=$this->getUser()->getId() && $message->getRead()==null){
                $message->setRead(1);
                $message->save();
            }
        }
        foreach($firsts as $first){
            /** @var Message $first */
            if($first->getFromId()!=$this->getUser()->getId() && $first->getRead()==null){
                $first->setRead(1);
                $first->save();
            }
        }

        if ($request->getMethod() == "POST") {
            ini_set('date.timezone', 'Asia/Bangkok');
            $toId        = $_POST['toId'];
            $fromId      = $this->getUser()->getId();
            $originalId  = $_POST['originalId'];
            $description = $_POST['description'];

            $newMessage = new Message();
            $newMessage->setFromId($fromId);
            $newMessage->setToId($toId);
            $newMessage->setOriginalId($originalId);
            $newMessage->setDescription($description);
            $newMessage->save();

            return new RedirectResponse($this->generateUrl('pgs_core_message_view', ['id'=>$originalId]));
        }

        return [
            'model'      => 'Message',
            'firsts'     => $firsts,
            'originalId' => $id,
            'messages'   => $messages,
            'user'       => $user,
            'userId'     => $userId
        ];
    }

    /**
     *
     * @Template("PGSCoreDomainBundle:Message:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $user       = $this->getUser()->getUserProfile();
        if($this->isGranted('ROLE_ADMIN')){
            $userProfiles = $this->userProfileManager->findAll();
        }
        else{
            $userProfiles = $this->userProfileManager->findByOrganization($this->getActivePreference()->getCurrentUserProfile()->getOrganizationId());
        }

        $message = new Message();

        if (!$message = $this->messageManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_message_list'));
        }

        $form = $this->createForm($this->get('pgs.core.form.type.message'), $message);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                ini_set('date.timezone', 'Asia/Bangkok');
                $toId=$_POST['toId'];
                $message->setToId($toId);
                $message->setFromId($this->getUser()->getId());
                $message->setRead(0);
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_message_list'));
            }
        }

        return [
            'model'    => 'Message',
            'message'  => $message,
            'user'     => $user,
            'userProfiles'     => $userProfiles,
            'form'     => $form->createView()
        ];
    }

    /**
     * @param Request $request
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id'); //originalId

        $firsts     = $this->messageManager->findById($id);
        $messages   = $this->messageManager->findByOriginalId($id);

        foreach($messages as $message){
            /** @var Message $message */
            $message->delete();
        }
        foreach($firsts as $first){
            /** @var Message $first */
            $first->delete();
        }

        return new RedirectResponse($this->generateUrl('pgs_core_message_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Message $message*/
        $message = $form->getData();
        $message->save();
    }


}




