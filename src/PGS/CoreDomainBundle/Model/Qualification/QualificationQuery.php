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

namespace PGS\CoreDomainBundle\Model\Qualification;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Qualification\om\BaseQualificationQuery;

/**
 * @Service("pgs.core.query.qualification")
 */
class QualificationQuery extends BaseQualificationQuery
{
}