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
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentCondition;
use PGS\CoreDomainBundle\Model\StudentCondition\StudentConditionQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student_condition")
 */
class StudentConditionManager extends Authorizer
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
     * @var StudentConditionQuery
     */
    private $studentConditionQuery;


    /**
     * @InjectParams({
     *      "studentConditionQuery" = @Inject("pgs.core.query.student_condition"),
     *      "activePreference"      = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"       = @Inject("security.context"),
     * })
     */
    public function __construct(
        StudentConditionQuery $studentConditionQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference      = $activePreference;
        $this->studentConditionQuery = $studentConditionQuery;
        $this->securityContext       = $securityContext;
    }

    /**
     * @return StudentConditionQuery
     */
    public function getBaseQuery()
    {
        return $this->studentConditionQuery->create();
    }

    /**
     * @return StudentCondition[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param $schoolHealthId
     * @return StudentCondition
     */
    public function findAllBySchoolHealth($schoolHealthId)
    {
        if ($schoolHealthId instanceof SchoolHealth)
        {
            $schoolHealthId = $schoolHealthId->getId();
        }
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolHealthId($schoolHealthId)
            ->find();
    }

    /**
     * @param $schoolHealthId
     * @param $conditionId
     * @return StudentCondition
     */
    public function findOne($schoolHealthId, $conditionId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolHealthId($schoolHealthId)
            ->filterByConditionId($conditionId)
            ->findOne();
    }

    /**
     * @param StudentConditionQuery $query
     * @return bool|StudentConditionQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(StudentCondition $studentCondition)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    public function canAdd()
    {
        // TODO: Implement canAdd() method.
    }

    /** @param mixed $modelIdentifier */
    public function canEdit($modelIdentifier)
    {
        // TODO: Implement canEdit() method.
    }

    /** @param mixed $modelIdentifier */
    public function canView($modelIdentifier)
    {
        // TODO: Implement canView() method.
    }


    /**
     * @param mixed $studentCondition
     * @return bool|StudentCondition
     */
    public function canDelete($studentCondition)
    {
        $studentCondition = $this->findOne($studentCondition);

        if(!$this->limitRole($studentCondition)){
            return false;
        }

        return $studentCondition;
    }
}
