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
use PGS\CoreDomainBundle\Model\Message\Message;
use PGS\CoreDomainBundle\Model\Message\MessagePeer;
use PGS\CoreDomainBundle\Model\Message\MessageQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;


/**
 * @Service("pgs.core.manager.message")
 */
class MessageManager extends Authorizer
{

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "messageQuery" = @Inject("pgs.core.query.message")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        MessageQuery $messageQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->messageQuery = $messageQuery;
    }

    /**
     * @return Message
     */
    public function getBaseQuery()
    {
        return $this->messageQuery->create();
    }

    /**
     * @return message
     */
    public function getDefault()
    {
        if(!$message = $this->getCurrentUser()->getUserProfile()->getId()){
            $message = new Message();
        }

        return $message;
    }

    /**
     * @param mixed $message
     *
     * @return Message
     */
    public function findOne($message)
    {
        if ($message instanceof Message) {
            $message = $message->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($message);
    }

    /**
     * @return Message[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return Message[]
     */
    public function findSome()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }
//
//    /**
//     * @return Message[]
//     */
//    public function findGroupRecipient()
//    {
//        return $this->limitList($this->getBaseQuery())->groupByRecipient()->find();
//    }

    /**
     * @param mixed $message
     *
     * @return Message
     */
    public function findById($id)
    {
        return $this
            ->limitList($this->getBaseQuery())
            ->filterById($id)
            ->find();
    }

    /**
     * @param mixed $message
     *
     * @return Message
     */
    public function findByOriginalId($id)
    {
        return $this
            ->limitList($this->getBaseQuery())
            ->filterByOriginalId($id)
            ->find();
    }

    /**
     * @param mixed $message
     *
     * @return Message
     */
    public function findMessageHead()
    {
        return $this
            ->limitList($this->getBaseQuery())
            ->filterByOriginalId(null)
            ->find();
    }

    /**
     *
     * @return int
     */
    public function countUnreadByOriginalId($id)
    {
        return $this
            ->limitList($this->getBaseQuery())
            ->filterByOriginalId($id)
            ->filterByToId($this->activePreference->getCurrentUserProfile()->getId())
            ->filterByRead(0)
            ->count();
    }

    /**
     * @return bool|Message
     */
    public function canAdd()
    {
        if ($this->isAdmin() || $this->isTeacher() || $this->isPrincipal() || $this->isStudent() || $this->isParent()) {
            return new Message();
        }

        return false;
    }

    /**
     * @param MessageQuery $query
     *
     * @return bool|MessageQuery
     */
    public function limitList($query)
    {
//        if ($this->isAdmin()) {
            return $query;
//        }
//        elseif($this->isTeacher()){
//            return $query
//                ->useSchoolClassStudentQuery()
//                    ->useSchoolClassQuery()
//                        ->filterByPrimaryTeacherId($this->activePreference->getCurrentUserProfile()->getId())
//                    ->endUse()
//                ->endUse()
//            ;
//        }
//        elseif ($this->isParent()) {
//            return $query
//                ->useSchoolClassStudentQuery()
//                    ->useStudentQuery()
//                        ->useParentStudentQuery()
//                            ->filterByUserId($this->activePreference->getCurrentUserProfile()->getId())
//                        ->endUse()
//                    ->endUse()
//                ->endUse()
//            ;
//        }
//        else{
//            echo('Please Login or Contact your Teacher / Principal / Administrator');die;
//        }
    }

    public function limitRole(Message $message)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $message
     *
     * @return bool|Message
     */
    public function canEdit($message)
    {
        $message = $this->findOne($message);

        if ($this->limitRole($message)) {
            return false;
        }

        return $message;
    }

    /**
     * @param mixed $message
     *
     * @return bool|Message
     */
    public function canDelete($message)
    {
        $message = $this->findOne($message);

        if ($this->limitRole($message)) {
            return false;
        }

        return $message;
    }

    /**
     * @param mixed $message
     *
     * @return bool|Message
     */
    public function canView($message)
    {
        $message = $this->findOne($message);

        if ($this->limitRole($message)) {
            return false;
        }

        return $message;
    }


}
