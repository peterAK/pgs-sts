<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PGS\CoreDomainBundle\Model\Country;
use PGS\CoreDomainBundle\Model\State;
use PGS\CoreDomainBundle\Repository\StateRepository;

/**
 * @Service("pgs.core.manager.state")
 */
class StateManager
{
    private $stateRepository;

    /**
     * @InjectParams({
     *      "stateRepository" = @Inject("pgs.core.repository.state")
     * })
     */
    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * @param mixed $country
     * @return State[]
     */
    public function getAllStatesByCountry($country)
    {
        if ($country instanceof Country) {
            $country = $country->getId();
        }

        return $this->stateRepository->create()->filterByCountryId($country)->find();
    }
}
