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
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTerm;
use PGS\CoreDomainBundle\Model\SchoolTerm\SchoolTermQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use PGS\CoreDomainBundle\Security\Model\SchoolYearAuthorizationStrategy;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_term")
 */
class SchoolTermManager extends Authorizer
{
    /**
     * @var SchoolTermQuery
     */
    private $schoolTermQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "schoolTermQuery"  = @Inject("pgs.core.query.school_term")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolTermQuery $schoolTermQuery
    ) {
        $this->activePreference = $activePreference;
        $this->schoolTermQuery  = $schoolTermQuery;
    }

    /**
     * @return SchoolTermQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolTermQuery->create();
    }

    /**
     * @param mixed $schoolTerm
     *
     * @return SchoolTerm
     */
    public function findOne($schoolTerm)
    {
        if ($schoolTerm instanceof SchoolTerm) {
            $schoolTerm = $schoolTerm->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolTerm);
    }

    /**
     * @return SchoolTermQuery
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery())->find();
    }

    /**
     * @param mixed $school
     *
     * @return SchoolTerm[]
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
     * @return SchoolTerm|null
     */
    public function findLatestActiveBySchool($school)
    {
        if ($school instanceof School)
        {
            $school = $school->getId();
        }

        $query = $this->getBaseQuery()->filterBySchoolId($school);
        $query = $query->orderByDateEnd();

        return $query->findOne();
    }

    /**
     * @param mixed $schoolTerm
     */
    public function setActive($schoolTerm)
    {
        if ($school = $this->activePreference->getSchoolPreference() || $this->isAdmin()) {
            if ($schoolTerm instanceof SchoolTerm) {
                $schoolTerm = $schoolTerm->getId();
            }
            $this->limitList($this->getBaseQuery())->makeActive($schoolTerm);
        }
    }

    /**
     * @param SchoolTerm $schoolTerm
     *
     * @param string     $direction
     *
     * @return bool
     */
    public function moveSchoolYear($schoolTerm, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $schoolTerm->moveToTop();
                break;

            case 'UP':
                $schoolTerm->moveUp();
                break;

            case 'DOWN':
                $schoolTerm->moveDown();
                break;

            case 'BOTTOM':
                $schoolTerm->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @param SchoolTermQuery $query
     *
     * @return bool|SchoolTermQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(SchoolTerm $schoolTerm)
    {
        if ($this->isAdmin())
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool|SchoolTerm
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolTerm();
        }

        return false;
    }

    /**
     * @param mixed $schoolTerm
     *
     * @return bool|SchoolTerm
     */
    public function canEdit($schoolTerm)
    {
        $schoolTerm = $this->findOne($schoolTerm);

        if ($this->limitRole($schoolTerm)) {
            return false;
        }

        return $schoolTerm;
    }

    /**
     * @param mixed $schoolTerm
     *
     * @return bool|SchoolTerm
     */
    public function canDelete($schoolTerm)
    {
        $schoolTerm = $this->findOne($schoolTerm);

        if ($this->limitRole($schoolTerm)) {
            return false;
        }

        return $schoolTerm;
    }

    /**
     * @param mixed $schoolTerm
     *
     * @return bool|SchoolTerm
     */
    public function canView($schoolTerm)
    {
        $schoolTerm = $this->findOne($schoolTerm);

        if ($this->limitRole($schoolTerm)) {
            return false;
        }

        return $schoolTerm;
    }


}
