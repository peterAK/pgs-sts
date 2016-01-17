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
use PGS\CoreDomainBundle\Manager\TopicManager;
use PGS\CoreDomainBundle\Model\Topic;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TopicController extends AbstractCoreBaseController
{
    /**
     * @var TopicManager
     *
     * @Inject("pgs.core.manager.topic")
     */
    protected $topicManager;

    /**
     * @Template("PGSCoreDomainBundle:Topic:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $query    = $this->topicManager->findAll();

        $topics = $this->get('knp_paginator');
        $topics = $topics->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        $accesses = $this->topicManager->getAccesses();
        $active   = $request->get('public','All');

        return [
            'model'    => 'Topic',
            'topics'   => $topics,
//            'count'    => $count,
            'accesses' => $accesses,
            'active'   => $active
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:Topic:view.html.twig")
     */
    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        if (!$topic = $this->topicManager->canView($id)) {
            throw $this->createNotFoundException('Invalid `topic` given');
        }

        return [
            'model' => 'Topic',
            'topic' => $topic
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Topic:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $parentItemId = $request->get('parent', null);

        if (!$topic = $this->topicManager->canAdd()) {
            return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
        }

        if ($parentItemId !== null) {
            if (!$parentItem = $this->topicManager->findOne($parentItemId)) {
                throw $this->createNotFoundException('Invalid `parent topic` given');
            }
            $topic->setParent($parentItem);
            $topic->setKey($parentItem->getKey().".");
        }

        $form = $this->createForm($this->get('pgs.core.form.type.topic'), $topic, [ 'parentItemId' => $parentItemId]);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {

                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
            }
        }

        return [
            'model' => 'Topic',
            'topic' => $topic,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Template("PGSCoreDomainBundle:Topic:form.html.twig")
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if (!$topic = $this->topicManager->canEdit($id)) {
            throw $this->createNotFoundException('Invalid `topic` given');
        }

        $parent = $topic->getParent();

        if ($parent instanceof Topic) {
            $parentItemId = $parent->getId();
        } else {
            $parentItemId = null;
        }

        $form = $this->createForm($this->get('pgs.core.form.type.topic') , $topic, [ 'parentItemId' => $parentItemId]);

        if ($request->getMethod() == "POST") {
            $form->submit($request);

            if ($form->isValid()) {
                $this->processForm($form);

                return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
            }
        }

        return [
            'model' => 'Topic',
            'topic' => $topic,
            'form'  => $form->createView()
        ];
    }

    /**
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        if (!$topic = $this->topicManager->canDelete($id)) {
            throw $this->createNotFoundException('Invalid `topic` given');
        }

        $topic->delete();

        return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
    }

    /**
     * @param Form $form
     */
    private function processForm(Form $form)
    {
        /** @var Topic $topic */
        $topic = $form->getData();

        // check if topic is empty
        if ($topic->isNew()) {
            // check whether a parent item is passed
            if ($form["parentItem"]->getData() === null) {
                $topic->makeRoot();
            } else {
                $parentItem = $this->topicManager->findOne($form["parentItem"]->getData());
                $topic->insertAsLastChildOf($parentItem);
            }
        }

        $topic->save();
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
        $id = $request->get('id', null);
        $direction = $request->get('direction', null);

        if ($direction === null) {
            return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
        }

        if (!$topic = $this->topicManager->findOne($id)) {
            throw $this->createNotFoundException('Invalid `topic` given');
        }

        $this->topicManager->moveTopic($topic, $direction);

        return new RedirectResponse($this->generateUrl('pgs_core_topic_list'));
    }

    /**
     * @Template("PGSCoreDomainBundle:Topic:list_by_access.html.twig")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function viewTopicByAccessAction(Request $request)
    {
        $access = $request->get('access');

        $query    = $this->topicManager->findByAccess($access);

        $topics = $this->get('knp_paginator');
        $topics = $topics->paginate(
            $query,
            $request->query->get('pg',1) /*page number*/,
            $this->container->getParameter('record_per_page')
        );

        return [ 'topics' => $topics ];
    }
}
