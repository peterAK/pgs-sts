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
use PGS\CoreDomainBundle\Model\Organization\Organization;
use PGS\CoreDomainBundle\Model\School\School;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school")
 */
class SchoolManager extends Authorizer
{
    /**
     * @var SchoolQuery
     */
    private $schoolQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "schoolQuery"      = @Inject("pgs.core.query.school")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        SchoolQuery $schoolQuery
    ) {
        $this->activePreference = $activePreference;
        $this->schoolQuery      = $schoolQuery;
    }

    /**
     * @return SchoolQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolQuery->create();
    }

    /**
     * @param mixed $school
     *
     * @return School
     */
    public function findOne($school)
    {
        if ($school instanceof School)
        {
            $school = $school->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($school);
    }

    /**
     * @return SchoolQuery
     */
    public function findAll()
    {
        return $this->limitList($this->getBaseQuery());
    }


    /**
     * @param $school
     * @return School
     */
    public function findOneById($school)
    {
        if ($school instanceof School) {
            $school = $school->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($school);
    }

   
    /**
     * @param mixed $organization
     *
     * @return School[]
     */
    public function findByOrganization($organization)
    {
        if ($organization instanceof Organization)
        {
            $organization = $organization->getId();
        }
        return $this->getBaseQuery()->findByOrganizationId($organization);
    }

    /**
     * @param mixed $organization
     *
     * @return School
     */
    public function findOneByOrganization($organization)
    {
        if ($organization instanceof Organization)
        {
            $organization = $organization->getId();
        }
        return $this->getBaseQuery()->findOneByOrganizationId($organization);
    }

    /**
     * @param string $status
     *
     * @return School[]
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            $query = $this->getBaseQuery()->filterByStatus(strtolower($status));

            return $this->limit($query)->find();
        }
    }

    /**
     * @param string $status
     * @param string $confirmation
     *
     * @return School[]
     */
    public function findByStatusAndConfirmation($status, $confirmation)
    {
        $query = $this->getBaseQuery();

        if (strtoupper($status) !== 'ALL') {
            $query = $query->filterByStatus(strtolower($status));
        }

        if (strtoupper($confirmation) !=='ALL') {
            $query = $query->filterByConfirmation(strtolower($confirmation));
        }

        return $query->find();
    }

    /**
     * @return SchoolQuery
     */
    public function getSchoolChoices() {
        return $this->limitList($this->getBaseQuery())->orderByName();
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return School::getStatuses();
    }

    /**
     * @param SchoolQuery $query
     *
     * @return bool|SchoolQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(School $school)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|School
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new School();
        }

        return false;
    }

    /**
     * @param mixed $school
     *
     * @return bool|School
     */
    public function canEdit($school)
    {
        $school = $this->findOne($school);

        if ($this->limitRole($school)) {
            return false;
        }

        return $school;
    }

    /**
     * @param mixed $school
     *
     * @return bool|School
     */
    public function canDelete($school)
    {
        $school = $this->findOne($school);

        if ($this->limitRole($school)) {
            return false;
        }

        return $school;
    }

    /**
     * @param mixed $school
     *
     * @return bool|School
     */
    public function canView($school)
    {
        $school = $this->findOne($school);

        if ($this->limitRole($school)) {
            return false;
        }

        return $school;
    }


}
