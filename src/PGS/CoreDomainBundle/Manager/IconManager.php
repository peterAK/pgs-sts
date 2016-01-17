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
use PGS\CoreDomainBundle\Model\Icon\Icon;
use PGS\CoreDomainBundle\Model\Icon\IconQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Security\Model\IconAuthorizationStrategy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.icon")
 */
class IconManager extends Authorizer
{
    /**
     * @var IconQuery
     */
    private $iconQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "iconQuery" = @Inject("pgs.core.query.icon")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        IconQuery $iconQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->iconQuery = $iconQuery;
    }

    /**
     * @return IconQuery
     */
    public function getBaseQuery()
    {
        return $this->iconQuery->create();
    }

    /**
     * @return icon
     */
    public function getDefault()
    {
        if(!$icon = $this->getCurrentUser()->getUserProfile()->getIconId()){
            $icon = new Icon();
        }

        return $icon;
    }

    /**
     * @param mixed $icon
     *
     * @return Icon
     */
    public function findOne($icon)
    {
        if ($icon instanceof Icon) {
            $icon = $icon->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($icon);
    }

    /**
     * @return Icon[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param string $status
     *
     * @return Icon
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            $query = $this->limitList($this->getBaseQuery())->filterByStatus(strtolower($status));

            return $this->limit($query)->find();
        }
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return Icon::getStatuses();
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

    /**
     * @param Icon $icon
     * @param string $direction
     *
     * @return true
     */
    public function moveIcon(Icon $icon, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $icon->moveToTop();
                break;

            case 'UP':
                $icon->moveUp();
                break;

            case 'DOWN':
                $icon->moveDown();
                break;

            case 'BOTTOM':
                $icon->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @param IconQuery $query
     *
     * @return bool|IconQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }

        return $query->filterById($this->getCurrentUser()->getUserProfile()->getIconId());
    }

    public function limitRole(Icon $icon)
    {
        if($this->isAdmin() || $this->isTeacher() || $this->isPrincipal()){
            return false;
        }

    }

    /**
     * @return bool|Icon
     */
    public function canAdd()
    {
        if ($this->isAdmin() || $this->isTeacher() || $this->isPrincipal()) {
            return new Icon();
        }

        return false;
    }

    /**
     * @param mixed $icon
     *
     * @return bool|Icon
     */
    public function canEdit($icon)
    {
        $icon = $this->findOne($icon);

        if ($this->limitRole($icon)) {
            return false;
        }

        return $icon;
    }

    /**
     * @param mixed $icon
     *
     * @return bool|Icon
     */
    public function canDelete($icon)
    {
        $icon = $this->findOne($icon);

        if ($this->limitRole($icon)) {
            return false;
        }

        return $icon;
    }

    /**
     * @param mixed $icon
     *
     * @return bool|Icon
     */
    public function canView($icon)
    {
        $icon = $this->findOne($icon);

        if ($this->limitRole($icon)) {
            return false;
        }

        return $icon;
    }


}
