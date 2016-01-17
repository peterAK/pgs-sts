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
use PGS\CoreDomainBundle\Model\Grade\Grade;
use PGS\CoreDomainBundle\Model\Grade\GradeQuery;
use PGS\CoreDomainBundle\Repository\GradeRepository;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.grade")
 */
class GradeManager extends Authorizer
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
     * @var GradeQuery
     */
    private $gradeQuery;


    /**
     * @InjectParams({
     *      "gradeQuery"       = @Inject("pgs.core.query.grade"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        GradeQuery $gradeQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference = $activePreference;
        $this->gradeQuery = $gradeQuery;
        $this->securityContext = $securityContext;
    }

    /**
     * @return GradeQuery
     */
    public function getBaseQuery()
    {
        return $this->gradeQuery->create();
    }

    /**
     * @return Grade[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param mixed $grade
     *
     * @return Grade
     */
    public function findOne($grade)
    {
        if ($grade instanceof Grade) {
            $grade = $grade->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($grade);
    }

    /**
     * @param GradeQuery $query
     * @return bool|GradeQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Grade $grade)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Grade
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Grade;
        }
    }

    /**
     * @param mixed $grade
     * @return bool|Grade
     */
    public function canEdit($grade)
    {
        $grade = $this->findOne($grade);

         if($this->limitRole($grade)){
             return false;
         }

        return $grade;
    }

    /**
     * @param mixed $grade
     * @return bool|Grade
     */
    public function canView($grade)
    {
        $grade = $this->findOne($grade);

        if($this->limitRole($grade)){
            return false;
        }

        return $grade;
    }

    /**
     * @param mixed $grade
     * @return bool|Grade
     */
    public function canDelete($grade)
    {
        $grade = $this->findOne($grade);

        if($this->limitRole($grade)){
            return false;
        }

        return $grade;
    }


}
