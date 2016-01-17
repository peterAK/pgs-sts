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
use PGS\CoreDomainBundle\Model\Term\Term;
use PGS\CoreDomainBundle\Model\Term\TermQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.term")
 */
class TermManager extends Authorizer
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
     * @var TermQuery
     */
    private $termQuery;

    /**
     * @InjectParams({
     *      "termQuery" = @Inject("pgs.core.query.term"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        TermQuery $termQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->termQuery              = $termQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return termQuery
     */
    public function getBaseQuery()
    {
        return $this->termQuery->create();
    }

    /**
     * @return Term[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param mixed $term
     *
     * @return Term
     */
    public function findOne($term)
    {
        if ($term instanceof Term) {
            $term = $term->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($term);
    }

    /**
     * @param Term   $term
     * @param string $direction
     *
     * @return bool
     */
    public function moveTerm(Term $term, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $term->moveToTop();
                break;

            case 'UP':
                $term->moveUp();
                break;

            case 'DOWN':
                $term->moveDown();
                break;

            case 'BOTTOM':
                $term->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @param TermQuery $query
     * @return bool|TermQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Term $term)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Term
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Term();
        }
        return false;
    }

    /**
     * @param mixed $term
     * @return bool|Term
     */
    public function canEdit($term)
    {
        $term = $this->findOne($term);

        if($this->limitRole($term)){
            return false;
        }

        return $term;
    }

    /**
     * @param mixed $term
     * @return bool|Term
     */
    public function canDelete($term)
    {
        $term = $this->findOne($term);

        if($this->limitRole($term)){
            return false;
        }

        return $term;
    }

    /**
     * @param mixed $term
     * @return bool|Term
     */
    public function canView($term)
    {
        $term = $this->findOne($term);

        if($this->limitRole($term)){
            return false;
        }

        return $term;
    }

}
