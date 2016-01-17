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
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery;
use PGS\CoreDomainBundle\Security\Authorizer;

/**
 * @Service("pgs.core.manager.grade_level")
 */
class GradeLevelManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var GradeLevelQuery
     */
    private $gradeLevelQuery;

    /**
     * @InjectParams({
     *      "gradeLevelQuery" = @Inject("pgs.core.query.grade_level"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     * })
     */

    public function __construct(
        ActivePreferenceContainer $activePreference,
        GradeLevelQuery $gradeLevelQuery
    ) {
        $this->activePreference      = $activePreference;
        $this->gradeLevelQuery       = $gradeLevelQuery;
    }

    /**
     * @return GradeLevelQuery
     */
    public function getBaseQuery()
    {
        return $this->gradeLevelQuery->create();
    }

    /**
     * @return GradeLevel[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param mixed $gradeLevel
     *
     * @return GradeLevel
     */
    public function findOne($gradeLevel)
    {
        if ($gradeLevel instanceof GradeLevel) {
            $gradeLevel = $gradeLevel->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($gradeLevel);
    }

    /**
     * @param int $levelId
     * @param int $gradeId
     *
     * @return GradeLevel
     */
    public function checkExist($levelId, $gradeId)
    {
        return $this->getBaseQuery()->filterByLevelId($levelId)->filterByGradeId($gradeId)->findOne();
    }

    /**
     * @param GradeLevelQuery $query
     * @return bool|GradeLevelQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(GradeLevel $gradeLevel)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|GradeLevel
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new GradeLevel();
        }
        return false;
    }

    /**
     * @param mixed $gradeLevel
     *
     * @return bool|GradeLevel
     */
    public function canEdit($gradeLevel)
    {
        $gradeLevel = $this->findOne($gradeLevel);

        if ($this->limitRole($gradeLevel)) {
            return false;
        }

        return $gradeLevel;
    }

    /**
     * @param mixed $gradeLevel
     *
     * @return bool|GradeLevel
     */
    public function canDelete($gradeLevel)
    {
        $gradeLevel = $this->findOne($gradeLevel);

        if ($this->limitRole($gradeLevel)) {
            return false;
        }

        return $gradeLevel;
    }

    /**
     * @param mixed $gradeLevel
     *
     * @return bool|GradeLevel
     */
    public function canView($gradeLevel)
    {
        $gradeLevel = $this->findOne($gradeLevel);

        if ($this->limitRole($gradeLevel)) {
            return false;
        }

        return $gradeLevel;
    }

}
