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
use PGS\CoreDomainBundle\Model\Ethnicity\EthnicityI18n;
use PGS\CoreDomainBundle\Model\Ethnicity\om\BaseEthnicityI18nQuery;

/**
 * @Service("pgs.core.repository.ethnicity.i18n")
 */
class EthnicityI18nRepository extends BaseEthnicityI18nQuery
{

}
