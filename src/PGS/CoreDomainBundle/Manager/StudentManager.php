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
use PGS\CoreDomainBundle\Model\Student\Student;
use PGS\CoreDomainBundle\Model\Student\StudentQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student")
 */
class StudentManager extends Authorizer
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
     * @var StudentQuery
     */
    private $studentQuery;


    /**
     * @InjectParams({
     *      "studentQuery" = @Inject("pgs.core.query.student"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        StudentQuery $studentQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    )
    {
        $this->studentQuery          = $studentQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return studentQuery
     */
    public function getBaseQuery()
    {
        return $this->studentQuery->create();
    }

    /**
     * @return Student[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderByFirstName()->find();
    }

    /**
     * @return Student[]
     */
    public function findAllBySchool()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return Student[]
     */
    public function findAllBySchoolClass()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $student
     *
     * @return Student
     */
    public function findOne($student)
    {
        if ($student instanceof $student) {
            $student = $student->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($student);
    }

    /**
     * @param StudentQuery $query
     * @return bool|StudentQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Student $student)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Student
     */
    public function canAdd()
    {
        if($this->isAdmin() || $this->isTeacher() || $this->isPrincipal()){
            return new Student();
        }
        return false;
    }

    /**
     * @param mixed $student
     * @param bool|Student
     */
    public function canEdit($student)
    {
        $student = $this->findOne($student);

         if(!$this->limitRole($student)){
             return false;
         }

        return $student;
    }

    /**
     * @param mixed $student
     * @return bool|Student
     */
    public function canView($student)
    {
        $student = $this->findOne($student);

        if(!$this->limitRole($student)){
            return false;
        }

        return $student;
    }

    /**
     * @param mixed $modelIdentifier
     * @return bool|Student
     */
    public function canDelete($student)
    {
        $student = $this->findOne($student);

        if(!$this->limitRole($student)){
            return false;
        }

        return $student;
    }


}
