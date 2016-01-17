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
use PGS\CoreDomainBundle\Model\Help;
use PGS\CoreDomainBundle\Model\HelpQuery;
use PGS\CoreDomainBundle\Security\Authorizer;

/**
 * @Service("pgs.core.manager.help")
 */
class HelpManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public  $activePreference;

    /**
     * @var HelpQuery
     */
    private $helpQuery;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "helpQuery"        = @Inject("pgs.core.query.help")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        HelpQuery $helpQuery
    ) {
            $this->activePreference = $activePreference;
            $this->helpQuery        = $helpQuery;
    }

    /**
     * @return HelpQuery
     */
    public function getBaseQuery()
    {
        return $this->helpQuery->create();
    }

    /**
     * @param mixed $help
     *
     * @return Help
     */
    public function findOne($help)
    {
        if ($help instanceof Help) {
            $help = $help->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($help);

    }

    /**
     * @return Help[]
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery());
    }

    /**
     * @param string $key
     *
     * @return Help
     */
    public function findOneByKey($key)
    {
        return $this->getBaseQuery()
            ->filterByKey($key)
            ->findOne();
    }

    /**
     * @param HelpQuery $query
     *
     * @return bool|HelpQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Help $help)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Help
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Help();
        }

        return false;
    }

    /**
     * @param mixed $help
     *
     * @return bool|Help
     */
    public function canEdit($help)
    {
        $help = $this->findOne($help);

        if (!$this->limitRole($help)) {
            return false;
        }

        return $help;
    }

    /**
     * @param mixed $help
     *
     * @return bool|Help
     */
    public function canDelete($help)
    {
        $help = $this->findOne($help);

        if (!$this->limitRole($help)) {
            return false;
        }

        return $help;
    }

    /**
     * @param mixed $help
     *
     * @return bool|Help
     */
    public function canView($help)
    {
        $help = $this->findOne($help);

        if (!$this->limitRole($help)) {
            return false;
        }

        return $help;
    }
}
