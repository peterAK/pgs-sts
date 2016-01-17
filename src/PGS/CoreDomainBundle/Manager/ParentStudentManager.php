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
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudent;
use PGS\CoreDomainBundle\Model\ParentStudent\ParentStudentQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.parent_student")
 */
class ParentStudentManager extends Authorizer
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
     * @var ParentStudentQuery
     */
    private $parentStudentQuery;

    /**
     * @InjectParams({
     *      "parentStudentQuery"  = @Inject("pgs.core.query.parent_student"),
     *      "activePreference"    = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"     = @Inject("security.context"),
     * })
     */
    public function __construct(
        ParentStudentQuery $parentStudentQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference     = $activePreference;
        $this->parentStudentQuery   = $parentStudentQuery;
        $this->securityContext      = $securityContext;
    }

    /**
     * @return ParentStudentQuery
     */
    public function getBaseQuery()
    {
        return $this->parentStudentQuery->create();
    }

    /**
     * @return parentStudent
     */
    public function getDefault()
    {
        if(!$parentStudent = $this->getCurrentUser()->getUserProfile()->getId()){
            $parentStudent = new ParentStudent();
        }

        return $parentStudent;
    }

    /**
     * @param mixed $parentStudent
     *
     * @return ParentStudent
     */
    public function findOne($parentStudent)
    {
        if ($parentStudent instanceof ParentStudent) {
            $parentStudent = $parentStudent->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($parentStudent);
    }

    /**
     * @return ParentStudent[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @return ParentStudent[]
     */
    public function findSome()
    {
        $some= $this
            ->limitList($this->getBaseQuery());
        if($some!=null){
            return $some->find();
        }
        else{
            return 0;
        }
    }

    /**
     * @return ParentStudent[]
     */
    public function findByStudentId($studentId)
    {
        return $this
            ->getBaseQuery()
            ->filterByStudentId($studentId)
            ->find();
    }

    /**
     * @return bool|ParentStudent
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new ParentStudent();
        }

        return false;
    }

    /**
     * @param ParentStudentQuery $query
     *
     * @return bool|ParentStudentQuery
     */
    public function limitList($query)
    {
        if ($this->isParent()) {
            return $query
                ->filterByUserId($this->activePreference->getCurrentUserProfile()->getId())
            ;
        }
        else{
            return null;
        }
    }

    public function limitRole(ParentStudent $parentStudent)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $parentStudent
     *
     * @return bool|ParentStudent
     */
    public function canEdit($parentStudent)
    {
        $parentStudent = $this->findOne($parentStudent);

        if ($this->limitRole($parentStudent)) {
            return false;
        }

        return $parentStudent;
    }

    /**
     * @param mixed $parentStudent
     *
     * @return bool|ParentStudent
     */
    public function canDelete($parentStudent)
    {
        $parentStudent = $this->findOne($parentStudent);

        if ($this->limitRole($parentStudent)) {
            return false;
        }

        return $parentStudent;
    }

    /**
     * @param mixed $parentStudent
     *
     * @return bool|ParentStudent
     */
    public function canView($parentStudent)
    {
        $parentStudent = $this->findOne($parentStudent);

        if ($this->limitRole($parentStudent)) {
            return false;
        }

        return $parentStudent;
    }


}
