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
use PGS\CoreDomainBundle\Model\om\BaseHelpQuery;
use PGS\CoreDomainBundle\Model\Help;

/**
 * @Service("pgs.core.repository.help")
 */
class HelpRepository extends BaseHelpQuery
{
    public function myPreSelect(\PropelPDO $con=null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HelpPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        var_dump('here');die;
    }
}
