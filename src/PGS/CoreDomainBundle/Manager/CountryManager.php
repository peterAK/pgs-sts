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
use PGS\CoreDomainBundle\Repository\CountryRepository;

/**
 * @Service("pgs.core.manager.country")
 */
class CountryManager
{
    private $countryRepository;

    /**
     * @InjectParams({
     *      "countryRepository" = @Inject("pgs.core.repository.country")
     * })
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param mixed $country
     *
     * @return Country
     */
    public function findOne($country)
    {
        if ($country instanceof Country) {
            $country = $country->getId();
        }

        return $this->countryRepository->create()->findOneById($country);
    }

    /**
     * @return Country[]
     */
    public function findAll()
    {
        return $this->countryRepository->create()->find();
    }
}
