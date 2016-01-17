<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 1/20/2014.
 */

namespace PGS\CoreDomainBundle\Model\SchoolTerm;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\SchoolTerm\om\BaseSchoolTermQuery;

/**
 * @Service("pgs.core.query.school_term")
 */
class SchoolTermQuery extends BaseSchoolTermQuery
{
}
