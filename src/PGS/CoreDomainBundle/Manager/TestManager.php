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
use PGS\CoreDomainBundle\Model\Test\Test;
use PGS\CoreDomainBundle\Model\Test\TestQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;
/**
 * @Service("pgs.core.manager.test")
 */
class TestManager extends Authorizer
{
    /**
     * @var TestQuery
*/
    private $testQuery;

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @InjectParams({
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "testQuery"        = @Inject("pgs.core.query.test")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        TestQuery $testQuery
    ) {
        $this->activePreference = $activePreference;
        $this->testQuery        = $testQuery;
    }

    /**
     * @return TestQuery
     */
    public function getBaseQuery()
    {
        return $this->testQuery->create();
    }

    /**
     * @return Test[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     * @param mixed $test
     *
     * @return Test
     */
    public function findOne($test)
    {
        if ($test instanceof Test) {
            $test = $test->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($test);
    }

    /**
     * @param string $status
     *
     * @return Test
     */
    public function findByStatus($status)
    {
        if (strtoupper($status) == 'ALL') {
            return $this->findAll();
        } else {
            return $this->limitList($this->getBaseQuery())->findByStatus(strtolower($status));
        }
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return Test::getStatuses();
    }

    /**
     * @param mixed $query
     *
     * @return TestQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Test $test)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Test
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new Test();
        }

        return false;
    }

    /**
     * @param mixed $test
     *
     * @return bool|Test
     */
    public function canEdit($test)
    {
        $test = $this->findOne($test);

        if ($this->limitRole($test)) {
            return false;
        }

        return $test;
    }

    /**
     * @param mixed $test
     *
     * @return bool|Test
     */
    public function canDelete($test)
    {
        $test = $this->findOne($test);

        if ($this->limitRole($test)) {
            return false;
        }

        return $test;
    }

    /**
     * @param mixed $test
     *
     * @return bool|Test
     */
    public function canView($test)
    {
        $test = $this->findOne($test);

        if ($this->limitRole($test)) {
            return false;
        }

        return $test;
    }
}
