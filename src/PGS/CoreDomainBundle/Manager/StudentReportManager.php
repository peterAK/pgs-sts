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
use PGS\CoreDomainBundle\Model\StudentReport\StudentReport;
use PGS\CoreDomainBundle\Model\StudentHistory\StudentHistory;
use PGS\CoreDomainBundle\Model\StudentReport\StudentReportQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student_report")
 */
class StudentReportManager extends Authorizer
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
     * @var StudentReportQuery
     */
    private $studentReportQuery;


    /**
     * @InjectParams({
     *      "studentReportQuery"   = @Inject("pgs.core.query.student_report"),
     *      "activePreference"     = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"      = @Inject("security.context"),
     * })
     */
    public function __construct(
        StudentReportQuery $studentReportQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference     = $activePreference;
        $this->studentReportQuery   = $studentReportQuery;
        $this->securityContext      = $securityContext;
    }

    /**
     * @return StudentReportQuery
     */
    public function getBaseQuery()
    {
        return $this->studentReportQuery->create();
    }

    /**
     * @return StudentReport[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $studentReport
     *
     * @return StudentReport
     */
    public function findOne($studentReport)
    {
        if ($studentReport instanceof StudentReport) {
            $studentReport = $studentReport->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($studentReport);
    }

    /**
     * @param StudentReportQuery $query
     * @return bool|StudentReportQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(StudentReport $studentReport)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|StudentReport
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new StudentReport;
        }
    }

    /**
     * @param mixed $studentReport
     * @return bool|StudentReport
     */
    public function canEdit($studentReport)
    {
        $studentReport = $this->findOne($studentReport);

         if(!$this->limitRole($studentReport)){
             return false;
         }

        return $studentReport;
    }

    /**
     * @param mixed $studentReport
     * @return bool|StudentReport
     */
    public function canView($studentReport)
    {
        $studentReport = $this->findOne($studentReport);

        if(!$this->limitRole($studentReport)){
            return false;
        }

        return $studentReport;
    }

    /**
     * @param mixed $studentReport
     * @return bool|StudentReport
     */
    public function canDelete($studentReport)
    {
        $studentReport = $this->findOne($studentReport);

        if(!$this->limitRole($studentReport)){
            return false;
        }

        return $studentReport;
    }


}
