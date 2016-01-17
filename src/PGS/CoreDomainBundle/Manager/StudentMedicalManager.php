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
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedical;
use PGS\CoreDomainBundle\Model\StudentMedical\StudentMedicalQuery;
use PGS\CoreDomainBundle\Model\SchoolHealth\SchoolHealth;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student_medical")
 */
class StudentMedicalManager extends Authorizer
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
     * @var StudentMedicalQuery
     */
    private $studentMedicalQuery;


    /**
     * @InjectParams({
     *      "studentMedicalQuery"   = @Inject("pgs.core.query.student_medical"),
     *      "activePreference"      = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"       = @Inject("security.context"),
     * })
     */
    public function __construct(
        StudentMedicalQuery $studentMedicalQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference    = $activePreference;
        $this->studentMedicalQuery = $studentMedicalQuery;
        $this->securityContext     = $securityContext;
    }

    /**
     * @return StudentMedicalQuery
     */
    public function getBaseQuery()
    {
        return $this->studentMedicalQuery->create();
    }

    /**
     * @return StudentMedical[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param $schoolHealthId
     * @return StudentMedical
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
     * @param $medicalId
     * @return StudentMedical
     */
    public function findOne($schoolHealthId, $medicalId)
    {

        return $this->limitList($this->getBaseQuery())
            ->filterBySchoolHealthId($schoolHealthId)
            ->filterByMedicalId($medicalId)
            ->findOne();
    }

    /**
     * @param StudentMedicalQuery $query
     * @return bool|StudentMedicalQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(StudentMedical $studentMedical)
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
    public function canDelete($modelIdentifier)
    {
        // TODO: Implement canDelete() method.
    }

    /** @param mixed $modelIdentifier */
    public function canView($modelIdentifier)
    {
        // TODO: Implement canView() method.
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

}



