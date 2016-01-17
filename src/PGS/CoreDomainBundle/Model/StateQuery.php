<?php

namespace PGS\CoreDomainBundle\Model;

use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\om\BaseStateQuery;
use PGS\CoreDomainBundle\Model\Country;

/**
 * @Service("pgs.core.query.state")
 */
class StateQuery extends BaseStateQuery
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
