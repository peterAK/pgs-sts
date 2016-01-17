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
use PGS\CoreDomainBundle\Model\Formula\Formula;
use PGS\CoreDomainBundle\Model\Formula\FormulaQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.formula")
 */
class FormulaManager extends Authorizer
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
     * @var FormulaQuery
     */
    private $formulaQuery;


    /**
     * @InjectParams({
     *      "formulaQuery"     = @Inject("pgs.core.query.formula"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        FormulaQuery $formulaQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference = $activePreference;
        $this->formulaQuery   = $formulaQuery;
        $this->securityContext  = $securityContext;
    }

    /**
     * @return FormulaQuery
     */
    public function getBaseQuery()
    {
        return $this->formulaQuery->create();
    }

    /**
     * @return Formula[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $formula
     *
     * @return Formula
     */
    public function findOne($formula)
    {
        if ($formula instanceof Formula) {
            $formula = $formula->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($formula);
    }

    /**
     * @param FormulaQuery $query
     * @return bool|FormulaQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Formula $formula)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Formula
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Formula;
        }
    }

    /**
     * @param mixed $formula
     * @return bool|Formula
     */
    public function canEdit($formula)
    {
        $formula = $this->findOne($formula);

         if(!$this->limitRole($formula)){
             return false;
         }

        return $formula;
    }

    /**
     * @param mixed $formula
     * @return bool|Formula
     */
    public function canView($formula)
    {
        $formula = $this->findOne($formula);

        if(!$this->limitRole($formula)){
            return false;
        }

        return $formula;
    }

    /**
     * @param mixed $formula
     * @return bool|Formula
     */
    public function canDelete($formula)
    {
        $formula = $this->findOne($formula);

        if(!$this->limitRole($formula)){
            return false;
        }

        return $formula;
    }


}
