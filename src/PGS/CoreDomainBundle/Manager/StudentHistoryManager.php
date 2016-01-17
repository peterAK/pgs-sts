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
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistoryQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student.history")
 */
class StudentHistoryManager extends Authorizer
{


    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var StudentHistoryQuery
     */
    private $studentHistoryQuery;


    /**
     * @InjectParams({
     *      "studentHistoryQuery"   = @Inject("pgs.core.query.student_history"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        StudentHistoryQuery $studentHistoryQuery,
        SecurityContext $securityContext
    ){
        $this->studentHistoryQuery   = $studentHistoryQuery;
        $this->securityContext       = $securityContext;
    }

    /**
     * @return StudentHistoryQuery
     */
    public function getBaseQuery()
    {
        return $this->studentHistoryQuery->create();
    }

    /**
     * @return StudentHistory[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $studentHistory
     *
     * @return StudentHistory
     */
    public function findOne($studentHistory)
    {
        if ($studentHistory instanceof StudentHistory) {
            $studentHistory = $studentHistory->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($studentHistory);
    }

    /**
     * @param $studentId
     * @return StudentHistory
     */
    public function findOneByStudent($studentId)
    {
        return $this->limitList($this->getBaseQuery())
            ->filterByStudentId($studentId)
            ->findOne();
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return StudentHistory::getStatuses();
    }

    /**
     * @param StudentHistoryQuery $query
     * @return bool|StudentHistoryQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(StudentHistory $studentHistory)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|StudentHistory
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new StudentHistory;
        }
    }

    /**
     * @param mixed $studentHistory
     * @return bool|StudentHistory
     */
    public function canEdit($studentHistory)
    {
        $condition = $this->findOne($studentHistory);

         if(!$this->limitRole($studentHistory)){
             return false;
         }

        return $studentHistory;
    }

    /**
     * @param mixed $studentHistory
     * @return bool|StudentHistory
     */
    public function canView($studentHistory)
    {
        $studentHistory = $this->findOne($studentHistory);

        if(!$this->limitRole($studentHistory)){
            return false;
        }

        return $studentHistory;
    }

    /**
     * @param mixed $studentHistory
     * @return bool|StudentHistory
     */
    public function canDelete($studentHistory)
    {
        $studentHistory = $this->findOne($studentHistory);

        if(!$this->limitRole($studentHistory)){
            return false;
        }

        return $studentHistory;
    }


}
