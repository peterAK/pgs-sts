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
use PGS\CoreDomainBundle\Model\Level\Level;
use PGS\CoreDomainBundle\Model\Level\LevelQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.level")
 */
class LevelManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var LevelQuery
     */
    private $levelQuery;

    /**
     * @InjectParams({
     *      "levelQuery"       = @Inject("pgs.core.query.level"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        LevelQuery $levelQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ) {
        $this->levelQuery             = $levelQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;

    }

    /**
     * @return levelQuery
     */
    public function getBaseQuery()
    {
        return $this->levelQuery->create();
    }

    /**
     * @return Level[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param mixed $level
     * @return Level
     */
    public function findOne($level)
    {
        if ($level instanceof Level) {
            $level = $level->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($level);
    }

    /**
     * @param LevelQuery $query
     * @return bool|LevelQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Level $level)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Level
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Level();
        }
        return false;
    }

    /**
     * @param mixed $level
     * @return bool|Level
     */
    public function canEdit($level)
    {
        $level = $this->findOne($level);

        if($this->limitRole($level)){
            return false;
        }

        return $level;
    }

    /**
     * @param mixed $level
     * @return bool|Level
     */
    public function canDelete($level)
    {
        $level=$this->findOne($level);

        if($this->limitRole($level)){
            return false;
        }

        return $level;
    }

    /**
     * @param mixed $level
     * @return bool|Level
     */
    public function canView($level)
    {
        $level=$this->findOne($level);

        if($this->limitRole($level)){
            return false;
        }

        return $level;
    }

}
