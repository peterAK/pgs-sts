<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Manager\CountryManager;
use PGS\CoreDomainBundle\Manager\StateManager;
use Symfony\Component\HttpFoundation\Response;

class StateController extends AbstractCoreBaseController
{
    /**
     * @var StateManager
     * @Inject("pgs.core.manager.state")
     */
    protected $stateManager;

    /**
     * @var CountryManager
     * @Inject("pgs.core.manager.country")
     */
    protected $countryManager;

    /**
     * @param int $id
     * @return Response
     */
    public function updateStateByCountryAction($id)
    {
        $states = [];
        if ($country = $this->countryManager->findOne($id))
        {
            $states = $this->stateManager->getAllStatesByCountry($id);
        }

        return $this->render(
            'PGSCoreDomainBundle:State:update_by_country.html.twig',
            [ 'states' => $states ]
        );
    }
}
