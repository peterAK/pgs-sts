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
use PGS\CoreDomainBundle\Model\Course\Course;
use PGS\CoreDomainBundle\Model\Course\CourseQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.course")
 */
class CourseManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public  $activePreference;

    /**
     * @var CourseQuery
     */
    private $courseQuery;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "courseQuery" = @Inject("pgs.core.query.course"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        CourseQuery $courseQuery,
        SecurityContext $securityContext
    )
    {
        $this->activePreference       = $activePreference;
        $this->courseQuery            = $courseQuery;
        $this->securityContext        = $securityContext;
    }

    /**
     * @return CourseQuery
     */
    public function getBaseQuery()
    {
        return $this->courseQuery->create();
    }

    /**
     * @return Course[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()
            ->orderByName()
            ->find()
            ;
    }

    /**
     * @param mixed $course
     *
     * @return Course
     */
    public function findOne($course)
    {
        if ($course instanceof Course) {
            $course = $course->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($course);
    }

    /**
     * @param CourseQuery $query
     * @return bool|CourseQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Course $course)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Course
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Course();
        }
        return false;
    }

    /**
     * @param mixed $course
     * @return bool|Course
     */
    public function canEdit($course)
    {
        $course = $this->findOne($course);

        if($this->limitRole($course)){
            return false;
        }

        return $course;
    }

    /**
     * @param mixed $course
     * @return bool|Course
     */
    public function canDelete($course)
    {
        $course = $this->findOne($course);

        if($this->limitRole($course)){
            return false;
        }

        return $course;
    }

    /**
     * @param mixed $course
     * @return bool|Course
     */
    public function canView($course)
    {
        $course = $this->findOne($course);

        if($this->limitRole($course)){
            return false;
        }

        return $course;
    }

}
