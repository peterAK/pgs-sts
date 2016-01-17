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
use PGS\CoreDomainBundle\Model\Employee\Employee;
use PGS\CoreDomainBundle\Repository\EmployeeRepository;

/**
 * @Service("pgs.core.manager.employee")
 */
class EmployeeManager
{
    private $employeeRepository;

    /**
     * @InjectParams({
     *      "employeeRepository" = @Inject("pgs.core.repository.employee")
     * })
     */
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @return Employee[]
     */
    public function findAll()
    {
        return $this->employeeRepository
            ->create()
            ->find()
            ;
    }

    /**
     * @param mixed $employee
     *
     * @return Employee
     */
    public function findOne($employee)
    {
        if ($employee instanceof Employee) {
            $employee = $employee->getId();
        }

        return $this->employeeRepository->create()->findOneById($employee);
    }
}
