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
use PGS\CoreDomainBundle\Model\om\BaseUserQuery;

/**
 * @Service("pgs.core.repository.user")
 */
class UserRepository extends BaseUserQuery
{

}
