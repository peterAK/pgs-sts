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
use PGS\CoreDomainBundle\Model\Avatar\Avatar;
use PGS\CoreDomainBundle\Model\Avatar\AvatarQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.avatar")
 */
class AvatarManager extends Authorizer
{

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "avatarQuery" = @Inject("pgs.core.query.avatar")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        AvatarQuery $avatarQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->avatarQuery = $avatarQuery;
    }

    /**
     * @return AvatarQuery
     */
    public function getBaseQuery()
    {
        return $this->avatarQuery->create();
    }

    /**
     * @return avatar
     */
    public function getDefault()
    {
        if(!$avatar = $this->getCurrentUser()->getUserProfile()->getId()){
            $avatar = new Avatar();
        }

        return $avatar;
    }

    /**
     * @param mixed $avatar
     *
     * @return Avatar
     */
    public function findOne($avatar)
    {
        if ($avatar instanceof Avatar) {
            $avatar = $avatar->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($avatar);
    }

    /**
     * @return Avatar[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderByMinPoint(\Criteria::ASC)->find();
    }

    /**
     * @param mixed $avatar
     *
     * @return Avatar
     */
    public function findById($avatar)
    {
        return $this->getBaseQuery()->filterById($avatar)->find();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getBaseQuery()->create()->count();
    }

    /**
     * @return int
     */
    public function countActive()
    {
        return $this->getBaseQuery()->filterByStatus('active')->count();
    }

//    /**
//     * @param Avatar $avatar
//     * @param string $direction
//     *
//     * @return true
//     */
//    public function Avatar(Avatar $avatar, $direction) {
//        switch (strtoupper($direction)) {
//            case 'TOP':
//                $avatar->moveToTop();
//                break;
//
//            case 'UP':
//                $avatar->moveUp();
//                break;
//
//            case 'DOWN':
//                $avatar->moveDown();
//                break;
//
//            case 'BOTTOM':
//                $avatar->moveToBottom();
//                break;
//        }
//
//        return true;
//    }

    /**
     * @return bool|Avatar
     */
    public function canAdd()
    {
        if ($this->isAdmin() || $this->isTeacher()) {
            return new Avatar();
        }

        return false;
    }

    /**
     * @param AvatarQuery $query
     *
     * @return bool|AvatarQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin() || $this->isTeacher()) {
            return $query;
        }

        return $query->filterById($this->getCurrentUser()->getUserProfile()->getId());
    }

    public function limitRole(Avatar $avatar)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $avatar
     *
     * @return bool|Avatar
     */
    public function canEdit($avatar)
    {
        $avatar = $this->findOne($avatar);

        if ($this->limitRole($avatar)) {
            return false;
        }

        return $avatar;
    }

    /**
     * @param mixed $avatar
     *
     * @return bool|Avatar
     */
    public function canDelete($avatar)
    {
        $avatar = $this->findOne($avatar);

        if ($this->limitRole($avatar)) {
            return false;
        }

        return $avatar;
    }

    /**
     * @param mixed $avatar
     *
     * @return bool|Avatar
     */
    public function canView($avatar)
    {
        $avatar = $this->findOne($avatar);

        if ($this->limitRole($avatar)) {
            return false;
        }

        return $avatar;
    }


}
