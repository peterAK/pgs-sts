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
use PGS\CoreDomainBundle\Model\om\BaseStateQuery;
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\StateQuery;

/**
 * @Service("pgs.core.repository.state")
 */
class StateRepository extends BaseStateQuery
{
    /**
     * @return StateQuery
     */
    public function getNoStateChoice()
    {
        return $this->create()->filterById();
    }

    /**
     * @param Country $country
     *
     * @return StateQuery
     */
    public function getStatesByCountryChoices(Country $country)
    {
        return $this->create()->filterByCountry($country);
    }
}
