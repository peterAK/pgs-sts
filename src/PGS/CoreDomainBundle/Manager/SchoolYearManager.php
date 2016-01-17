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
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYear;
use PGS\CoreDomainBundle\Model\SchoolYear\SchoolYearQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Security\Model\SchoolYearAuthorizationStrategy;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_year")
 */
class SchoolYearManager extends Authorizer
{
    /**
     * @var SchoolYearQuery
     */
    private $schoolYearQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "schoolYearQuery"  = @Inject("pgs.core.query.school_year")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolYearQuery $schoolYearQuery
    ) {
        $this->activePreference = $activePreference;
        $this->schoolYearQuery  = $schoolYearQuery;
    }

    /**
     * @return SchoolYearQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolYearQuery->create();
    }

    /**
     * @param mixed $schoolYear
     *
     * @return SchoolYear
     */
    public function findOne($schoolYear)
    {
        if ($schoolYear instanceof SchoolYear) {
            $schoolYear = $schoolYear->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolYear);
    }

    /**
     * @return SchoolYearQuery
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @param mixed $school
     *
     * @return SchoolYear[]
     */
    public function findBySchool($school)
    {
        if ($school instanceof School)
        {
            $school = $school->getId();
        }

        $query = $this->getBaseQuery()->filterBySchoolId($school);

        return $query->find();
    }

    /**
     * @param mixed $school
     *
     * @return SchoolYear|null
     */
    public function findLatestActiveBySchool($school)
    {
        if ($school instanceof School)
        {
            $school = $school->getId();
        }

        $query = $this->getBaseQuery()->filterBySchoolId($school)->filterByActive(1);
        $query = $query->orderByDateEnd();

        return $query->findOne();
    }

    /**
     * @param mixed $schoolYear
     */
    public function setActive($schoolYear)
    {
        if ($school = $this->activePreference->getSchoolPreference() || $this->isAdmin()) {
            if ($schoolYear instanceof SchoolYear) {
                $schoolYear = $schoolYear->getId();
            }
            $this->limitList($this->getBaseQuery())->makeActive($schoolYear);
        }
    }

    /**
     * @param SchoolYear $schoolYear
     *
     * @param string     $direction
     *
     * @return bool
     */
    public function moveSchoolYear($schoolYear, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $schoolYear->moveToTop();
                break;

            case 'UP':
                $schoolYear->moveUp();
                break;

            case 'DOWN':
                $schoolYear->moveDown();
                break;

            case 'BOTTOM':
                $schoolYear->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @param SchoolYearQuery $query
     *
     * @return bool|SchoolYearQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(SchoolYear $schoolYear)
    {
        if ($this->isAdmin())
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool|SchoolYear
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolYear();
        }

        return false;
    }

    /**
     * @param mixed $schoolYear
     *
     * @return bool|SchoolYear
     */
    public function canEdit($schoolYear)
    {
        $schoolYear = $this->findOne($schoolYear);

        if ($this->limitRole($schoolYear)) {
            return false;
        }

        return $schoolYear;
    }

    /**
     * @param mixed $schoolYear
     *
     * @return bool|SchoolYear
     */
    public function canDelete($schoolYear)
    {
        $schoolYear = $this->findOne($schoolYear);

        if ($this->limitRole($schoolYear)) {
            return false;
        }

        return $schoolYear;
    }

    /**
     * @param mixed $schoolYear
     *
     * @return bool|SchoolYear
     */
    public function canView($schoolYear)
    {
        $schoolYear = $this->findOne($schoolYear);

        if ($this->limitRole($schoolYear)) {
            return false;
        }

        return $schoolYear;
    }


}
