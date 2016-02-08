<?php

namespace PGS\CoreDomainBundle\Model\Principal;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Principal\om\BasePrincipalQuery;

/**
 * @Service("pgs.core.query.principal")
 */
class PrincipalQuery extends BasePrincipalQuery
{
}
