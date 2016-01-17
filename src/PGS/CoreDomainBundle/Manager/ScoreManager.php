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
use PGS\CoreDomainBundle\Model\Score\Score;
use PGS\CoreDomainBundle\Model\Score\ScoreQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.score")
 */
class ScoreManager extends Authorizer
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
     * @var ScoreQuery
     */
    private $scoreQuery;


    /**
     * @InjectParams({
     *      "scoreQuery"       = @Inject("pgs.core.query.score"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        ScoreQuery $scoreQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference = $activePreference;
        $this->scoreQuery       = $scoreQuery;
        $this->securityContext  = $securityContext;
    }

    /**
     * @return ScoreQuery
     */
    public function getBaseQuery()
    {
        return $this->scoreQuery->create();
    }

    /**
     * @return Score[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param $studentId
     * @return ScoreQuery
     */
    public function findAllByStudent($studentId)
    {
        return $this->getBaseQuery()->filterByStudentId($studentId)->find();
    }
    /**
     * @param $schoolClassCourse
     * @param $schoolClassStudent
     * @return Score
     */
    public function findAllBySchoolClass($schoolClassCourse, $schoolClassStudent)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolClassCourse($schoolClassStudent)
            ->filterBySchoolClassStudentId($schoolClassStudent)
            ->find();
    }

    /**
     * @param $schoolClassStudent
     * @return Score
     */
    public function findAllBySchoolClassStudent($schoolClassStudent)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolClassStudentId($schoolClassStudent)
            ->find();
    }

    /**
     * @param mixed $score
     *
     * @return Score
     */
    public function findOne($score)
    {
        if ($score instanceof Score) {
            $score = $score->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($score);
    }

    /**
     * @param ScoreQuery $query
     * @return bool|ScoreQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Score $score)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Score
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Score;
        }
    }

    /**
     * @param mixed $score
     * @return bool|Score
     */
    public function canEdit($score)
    {
        $score = $this->findOne($score);

         if(!$this->limitRole($score)){
             return false;
         }

        return $score;
    }

    /**
     * @param mixed $score
     * @return bool|Score
     */
    public function canView($score)
    {
        $score = $this->findOne($score);

        if(!$this->limitRole($score)){
            return false;
        }

        return $score;
    }

    /**
     * @param mixed $score
     * @return bool|Score
     */
    public function canDelete($score)
    {
        $score = $this->findOne($score);

        if(!$this->limitRole($score)){
            return false;
        }

        return $score;
    }


}
