<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Repository;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Employee\om\BaseEmployeeQuery;

/**
 * @Service("pgs.core.repository.employee")
 */
class EmployeeRepository extends BaseEmployeeQuery
{

}
