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
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\CountryQuery;

/**
 * @Service("pgs.core.repository.country")
 */
class CountryRepository extends CountryQuery
{
    /**
     * @return Country[]
     */
    public function getAllCountries()
    {
        return $this->create()->orderByName()->find();
    }

    /**
     * @return CountryQuery
     */
    public function getCountryChoices()
    {
        return $this->create()->orderByName();
    }
}
