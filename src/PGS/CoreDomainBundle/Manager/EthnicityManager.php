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
use PGS\CoreDomainBundle\Model\Ethnicity\Ethnicity;
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.ethnicity")
  */
class EthnicityManager extends Authorizer
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
     * @var EthnicityQuery
     */
    private $ethnicityQuery;

    /**
     * @InjectParams({
     *      "ethnicityQuery"   = @Inject("pgs.core.query.ethnicity"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        EthnicityQuery $ethnicityQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->ethnicityQuery         = $ethnicityQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return ethnicityQuery
     */
    public function getBaseQuery()
    {
        return $this->ethnicityQuery->create();
    }

    /**
     * @return Ethnicity[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()
                ->useI18nQuery()
                ->orderByName()
                ->endUse()
            ->find();
    }

    /**
     * @param mixed $ethnicity
     *
     * @return Ethnicity
     */
    public function findOne($ethnicity)
    {
        if ($ethnicity instanceof Ethnicity) {
            $ethnicity = $ethnicity->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($ethnicity);
    }

    /**
     * @param EthnicityQuery $query
     * @return bool|EthnicityQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Ethnicity $ethnicity)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Ethnicity
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Ethnicity();
        }
        return false;
    }

    /**
     * @param mixed $ethnicity
     * @return bool|Ethnicity
     */
    public function canEdit($ethnicity)
    {
        $ethnicity = $this->findOne($ethnicity);

        if($this->limitRole($ethnicity)){
            return false;
        }
        return $ethnicity;
    }

    /**
     * @param mixed $ethnicity
     * @return bool|Ethnicity
     */
    public function canDelete($ethnicity)
    {
        $ethnicity = $this->findOne($ethnicity);

        if($this->limitRole($ethnicity)){
            return false;
        }
        return $ethnicity;
    }

    /**
     * @param mixed $ethnicity
     * @return bool|Ethnicity
     */
    public function canView($ethnicity)
    {
        $ethnicity = $this->findOne($ethnicity);

        if($this->limitRole($ethnicity)){
            return false;
        }
        return $ethnicity;
    }


}
