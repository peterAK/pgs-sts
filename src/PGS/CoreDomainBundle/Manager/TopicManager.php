<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Topic;
use PGS\CoreDomainBundle\Model\TopicQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.topic")
 */
class TopicManager extends Authorizer
{
//    use SchoolTrait;

    private $topicRepository;

    /**
     * @var ActivePreferenceContainer
     */
    public  $activePreference;

    /**
     * @var TopicQuery
     */
    private $topicQuery;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "topicQuery"       = @Inject("pgs.core.query.topic")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        TopicQuery $topicQuery
    ) {
        $this->activePreference = $activePreference;
        $this->topicQuery        = $topicQuery;
    }

    /**
     * @return TopicQuery
     */
    public function getBaseQuery()
    {
        return $this->topicQuery->create();
    }

    /**
     * @param mixed $topic
     *
     * @return Topic
     */
    public function findOne($topic)
    {
        if ($topic instanceof Topic)
        {
            $topic = $topic->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($topic);
    }

    /**
     * @return Topic[]
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery())->orderByTreeLeft();
    }

    /**
     * @param string $key
     *
     * @return Topic
     */
    public function findOneByKey($key)
    {
        return $this->limitList($this->getBaseQuery())->findOneByKey($key);
    }


    /**
     * @param TopicRepository $query
     *
     * @return TopicRepository
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException
     */
    public function restrictByAccessUser(TopicRepository $query)
    {
        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isPrincipal() || $this->isSchoolAdmin()) {
            return $query->filterByAccess('user');
        } else {
            throw new AccessDeniedException('Insufficient Access ');
        }
    }

    /**
     * @param string $access
     *
     * @return Topic[]
     */
    public function findByAccess($access)
    {
        if (strtoupper($access) == 'ALL') {
            return $this->findAll();
        } else {
            return $this->getBaseQuery()->orderByTreeLeft()->findByAccess(strtolower($access));
        }
    }

    /**
     * @return array
     */
    public function getAccesses()
    {
        return Topic::getAccesses();
    }

    /**
     * @param Topic  $topic
     * @param string $direction
     *
     * @return true
     */
    public function moveTopic(Topic $topic, $direction)
    {
        $parentNode = $topic->getParent();
        switch (strtoupper($direction)) {
            case 'TOP':
                $topic->moveToFirstChildOf($parentNode);
                break;

            case 'UP':
                if (!$prevSibling = $topic->getPrevSibling()) {
                    return true;
                } else {
                    $topic->moveToPrevSiblingOf($prevSibling);
                }
                break;

            case 'DOWN':
                if (!$nextSibling = $topic->getNextSibling()) {
                    return true;
                } else {
                    $topic->moveToNextSiblingOf($nextSibling);
                }
                break;

            case 'BOTTOM':
                $topic->moveToLastChildOf($parentNode);
                break;
        }
        return true;
    }

    /**
     * @param TopicQuery $query
     *
     * @return TopicQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Topic $topic)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Topic
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Topic();
        }

        return false;
    }

    /**
     * @param mixed $topic
     *
     * @return bool|Topic
     */
    public function canEdit($topic)
    {
        $topic = $this->findOne($topic);

        if (!$this->limitRole($topic)) {
            return false;
        }

        return $topic;
    }

    /**
     * @param mixed $topic
     *
     * @return bool|Topic
     */
    public function canDelete($topic)
    {
        $topic = $this->findOne($topic);

        if (!$this->limitRole($topic)) {
            return false;
        }

        return $topic;
    }

    /**
     * @param mixed $topic
     *
     * @return bool|Topic
     */
    public function canView($topic)
    {
        $topic = $this->findOne($topic);

        if (!$this->limitRole($topic)) {
            return false;
        }

        return $topic;
    }

    /**
     * Limit school choices by organization
     *
     * @param SecurityContext $securityContext
     * @param OldActivePreferenceContainer $activePreference
     *
     * @return object
     */
    public function getTopicChoices(
        SecurityContext $securityContext,
        OldActivePreferenceContainer $activePreference
    ) {
        $this->activePreference = $activePreference;
        $this->securityContext = $securityContext;

        return $this->restrictByAccessUser($this->topicRepository->create()->orderByTreeLeft()->orderByTitle());
    }
}
