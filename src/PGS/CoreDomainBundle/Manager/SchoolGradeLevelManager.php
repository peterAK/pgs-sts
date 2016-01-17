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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel;
use PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevelQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_grade_level")
 */
class SchoolGradeLevelManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public  $activePreference;

    /**
     * @var SchoolGradeLevelQuery
     */
    private $schoolGradeLevelQuery;

    /**
     * @InjectParams({
     *      "activePreference"          = @Inject("pgs.core.container.active_preference"),
     *      "schoolGradeLevelQuery"     = @Inject("pgs.core.query.school_grade_level")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolGradeLevelQuery $schoolGradeLevelQuery
    ) {
        $this->activePreference         = $activePreference;
        $this->schoolGradeLevelQuery    = $schoolGradeLevelQuery;
    }


    /**
     * @return SchoolGradeLevelQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolGradeLevelQuery->create();
    }


    public function findOne($schoolGradeLevel)
    {
        if ($schoolGradeLevel instanceof SchoolGradeLevel) {
            $schoolGradeLevel = $schoolGradeLevel->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolGradeLevel);
    }

    /**
     * @param mixed $school
     *
     * @return SchoolGradeLevel[]
     */
    public function findBySchool($school)
    {
        if ($school instanceof School)
        {
            $school = $school->getId();
        }

        return $this->limitList($this->getBaseQuery())->findBySchoolId($school);
    }

    /**
     * @return SchoolGradeLevel[]
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery());
    }

    /**
     * @param int $schoolGradeLevelId
     */
    public function makeActive($schoolGradeLevelId)
    {
        $schoolGradeLevel = $this->limitList($this->getBaseQuery())->findOneBySchoolId($schoolGradeLevelId);

        $this->limitList($this->getBaseQuery())
            ->filterBySchoolId($schoolGradeLevel)
            ->update(['Active' => false]);

        $schoolGradeLevel->setActive(true);
        $schoolGradeLevel->save();
    }

    /**
     * @param mixed $schoolGradeLevel
     */
    public function setActive($schoolGradeLevel)
    {
        if ($school = $this->activePreference->getSchoolPreference() || $this->isAdmin()) {
            if ($schoolGradeLevel instanceof SchoolGradeLevel) {
                $schoolGradeLevel = $schoolGradeLevel->getId();
            }
             return $this->limitList($this->getBaseQuery())->makeActive($schoolGradeLevel);
        }
    }


    /**
     * @param SchoolGradeLevel $schoolGradeLevel
     *
     * @param string     $direction
     *
     * @return bool
     */
    public function moveSchoolGradeLevel($schoolGradeLevel, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $schoolGradeLevel->moveToTop();
                break;

            case 'UP':
                $schoolGradeLevel->moveUp();
                break;

            case 'DOWN':
                $schoolGradeLevel->moveDown();
                break;

            case 'BOTTOM':
                $schoolGradeLevel->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @param SchoolGradeLevelQuery $query
     * @return bool|SchoolGradeLevelQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(SchoolGradeLevel $schoolGradeLevel)
    {
        if ($this->isAdmin())
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool|SchoolGradeLevel
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolGradeLevel();
        }

        return false;
    }

    /**
     * @param mixed $schoolGradeLevel
     *
     * @return bool|SchoolGradeLevel
     */
    public function canEdit($schoolGradeLevel)
    {
        $schoolGradeLevel = $this->findOne($schoolGradeLevel);

        if ($this->limitRole($schoolGradeLevel)) {
            return false;
        }

        return $schoolGradeLevel;
    }

    /**
     * @param mixed $schoolGradeLevel
     *
     * @return bool|SchoolGradeLevel
     */
    public function canDelete($schoolGradeLevel)
    {
        $schoolGradeLevel = $this->findOne($schoolGradeLevel);

        if ($this->limitRole($schoolGradeLevel)) {
            return false;
        }

        return $schoolGradeLevel;
    }

    /**
     * @param mixed $schoolGradeLevel
     *
     * @return bool|SchoolGradeLevel
     */
    public function canView($schoolGradeLevel)
    {
        $schoolGradeLevel = $this->findOne($schoolGradeLevel);

        if ($this->limitRole($schoolGradeLevel)) {
            return false;
        }

        return $schoolGradeLevel;
    }

}
