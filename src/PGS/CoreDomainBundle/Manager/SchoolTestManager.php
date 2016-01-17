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
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTest;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.school_test")
 */
class SchoolTestManager extends Authorizer
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
     * @var SchoolTestQuery
     */
    private $schoolTestQuery;

    /**
     * @InjectParams({
     *      "schoolTestQuery" = @Inject("pgs.core.query.school_test"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        SchoolTestQuery $schoolTestQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ) {
        $this->schoolTestQuery = $schoolTestQuery;
        $this->activePreference = $activePreference;
        $this->securityContext  = $securityContext;

    }

    /**
     * @return SchoolTestQuery
     */
    public function getBaseQuery()
    {
        return $this->schoolTestQuery->create();
    }

    public function findOne($schoolTest)
    {
        if ($schoolTest instanceof SchoolTest) {
            $schoolTest = $schoolTest->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($schoolTest);
    }

    /**
     * @param mixed $school
     *
     * @return SchoolTest[]
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
     * @return SchoolTest[]
     */
    public function findAll()
    {

        return $this->limitList($this->getBaseQuery())->orderBySchoolId()->find();
    }

    /**
     * @param string $status
     *
     * @return SchoolTest[]
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            return $this->limitList($this->getBaseQuery())->filterByStatus(strtolower($status))->find();
        }
    }

    /**
     * @param SchoolTest $schoolTest
     *
     * @param string     $direction
     *
     * @return bool
     */
    public function moveSchoolTest($schoolTest, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $schoolTest->moveToTop();
                break;

            case 'UP':
                $schoolTest->moveUp();
                break;

            case 'DOWN':
                $schoolTest->moveDown();
                break;

            case 'BOTTOM':
                $schoolTest->moveToBottom();
                break;
        }

        return true;
    }

    public function getStatuses()
    {
        return SchoolTest::getStatuses();
    }

    /**
     * @param SchoolTestQuery $query
     * @return bool|SchoolTestQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(SchoolTest $schoolTest)
    {
        if ($this->isAdmin())
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool|SchoolTest
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new SchoolTest();
        }

        return false;
    }

    /**
     * @param mixed $schoolTest
     *
     * @return bool|SchoolTest
     */
    public function canEdit($schoolTest)
    {
        $schoolTest = $this->findOne($schoolTest);

        if ($this->limitRole($schoolTest)) {
            return false;
        }

        return $schoolTest;
    }

    /**
     * @param mixed $schoolTest
     *
     * @return bool|SchoolTest
     */
    public function canDelete($schoolTest)
    {
        $schoolTest = $this->findOne($schoolTest);

        if ($this->limitRole($schoolTest)) {
            return false;
        }

        return $schoolTest;
    }

    /**
     * @param mixed $schoolTest
     *
     * @return bool|SchoolTest
     */
    public function canView($schoolTest)
    {
        $schoolTest = $this->findOne($schoolTest);

        if ($this->limitRole($schoolTest)) {
            return false;
        }

        return $schoolTest;
    }


}
