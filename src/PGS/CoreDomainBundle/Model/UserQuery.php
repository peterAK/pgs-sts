<?php

namespace PGS\CoreDomainBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\om\BaseUserQuery;

/**
 * @Service("pgs.core.query.user")
 */
class UserQuery extends BaseUserQuery
{
}
