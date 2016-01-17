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
use PGS\CoreDomainBundle\Model\Announcement\Announcement;
use PGS\CoreDomainBundle\Model\Announcement\AnnouncementPeer;
use PGS\CoreDomainBundle\Model\Announcement\AnnouncementQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;


/**
 * @Service("pgs.core.manager.announcement")
 */
class AnnouncementManager extends Authorizer
{

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "announcementQuery" = @Inject("pgs.core.query.announcement")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        AnnouncementQuery $announcementQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->announcementQuery = $announcementQuery;
    }

    /**
     * @return Announcement
     */
    public function getBaseQuery()
    {
        return $this->announcementQuery->create();
    }

    /**
     * @return announcement
     */
    public function getDefault()
    {
        if(!$announcement = $this->getCurrentUser()->getUserProfile()->getId()){
            $announcement = new Announcement();
        }

        return $announcement;
    }

    /**
     * @param mixed $announcement
     *
     * @return Announcement
     */
    public function findOne($announcement)
    {
        if ($announcement instanceof Announcement) {
            $announcement = $announcement->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($announcement);
    }

    /**
     * @return Announcement[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return Announcement[]
     */
    public function findSome()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @return Announcement[]
     */
    public function findNewest()
    {
        return $this->limitList($this->getBaseQuery())
            ->orderByCreatedAt(\Criteria::DESC)->findOne();
    }

    /**
     * @return int count
     */
    public function countAnnouncement()
    {
        return $this->limitList($this->getBaseQuery())->count();
    }

//    /**
//     * @param mixed $announcement
//     * @return Announcement[]
//     */
//    public function findNew()
//    {
//        return $this
//            ->getBaseQuery()
//            ->fil
//    }

    /**
     * @param mixed $announcement
     *
     * @return Announcement
     */
    public function findById($announcement)
    {
        return $this->getBaseQuery()->filterById($announcement)->find();
    }

    /**
     * @return bool|Announcement
     */
    public function canAdd()
    {
        if ($this->isAdmin() || $this->isTeacher()) {
            return new Announcement();
        }

        return false;
    }

    /**
     * @param AnnouncementQuery $query
     *
     * @return AnnouncementQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        elseif ($this->isPrincipal()) {
            $query
                ->condition(
                    "1",
                    AnnouncementPeer::RECIPIENT. " = 'principal'"
                )
                ->condition(
                    "2",
                    AnnouncementPeer::RECIPIENT. " = 'teacher'"
                )
                ->condition(
                    "3",
                    AnnouncementPeer::RECIPIENT. " = 'student'"
                )
                ->condition(
                    "4",
                    AnnouncementPeer::RECIPIENT. " = 'parent'"
                )
                ->condition(
                    "5",
                    AnnouncementPeer::RECIPIENT. " = 'studentParent'"
                )
            ;

            return $query->having([1,2,3,4,5], "or");
        }
        elseif($this->isTeacher()){
            $query
                ->condition(
                    "1",
                    AnnouncementPeer::RECIPIENT. " = 'teacher'"
                )
                ->condition(
                    "2",
                    AnnouncementPeer::RECIPIENT. " = 'student'"
                )
                ->condition(
                    "3",
                    AnnouncementPeer::RECIPIENT. " = 'parent'"
                )
                ->condition(
                    "4",
                    AnnouncementPeer::RECIPIENT. " = 'studentParent'"
                )
            ;

            return $query->having([1,2,3,4], "or");
        }
        elseif ($this->isStudent()) {
            $query
                ->condition(
                    "1",
                    AnnouncementPeer::RECIPIENT. " = 'student'"
                )
                ->condition(
                    "2",
                    AnnouncementPeer::RECIPIENT. " = 'studentParent'"
                )
            ;

            return $query->having([1,2], "or");
        }
        elseif ($this->isParent()) {
            $query
                ->condition(
                    "1",
                    AnnouncementPeer::RECIPIENT. " = 'parent'"
                )
                ->condition(
                    "2",
                    AnnouncementPeer::RECIPIENT. " = 'studentParent'"
                )
            ;

            return $query->having([1,2], "or");
        }
        else{
            echo('Please Login or Contact your Teacher / Principal / Administrator');die;
        }
    }

    public function limitRole(Announcement $announcement)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $announcement
     *
     * @return bool|Announcement
     */
    public function canEdit($announcement)
    {
        $announcement = $this->findOne($announcement);

        if ($this->limitRole($announcement)) {
            return false;
        }

        return $announcement;
    }

    /**
     * @param mixed $announcement
     *
     * @return bool|Announcement
     */
    public function canDelete($announcement)
    {
        $announcement = $this->findOne($announcement);

        if ($this->limitRole($announcement)) {
            return false;
        }

        return $announcement;
    }

    /**
     * @param mixed $announcement
     *
     * @return bool|Announcement
     */
    public function canView($announcement)
    {
        $announcement = $this->findOne($announcement);

        if ($this->limitRole($announcement)) {
            return false;
        }

        return $announcement;
    }


}
