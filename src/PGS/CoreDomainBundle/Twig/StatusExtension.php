<?php

/*
* This file is part of the PGS/CoreDomainBundle package.
*
* (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace PGS\CoreDomainBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use PGS\CoreDomainBundle\Model\UserPeer;

/**
 * @Service("pgs.core.extension.status")
 * @Tag("twig.extension")
 */
class StatusExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('status', array($this, 'statusFilter')),
        );
    }

    /**
     * @param int $status
     *
     * @return null|string
     */
    public function statusFilter($status) {
        switch($status) {
            case 0:
                return 'New';
            case 1:
                return 'Active';
            case 2:
                return 'Inactive';
            default:
                return 'Unknown';
        }
    }

    public function getName()
    {
        return 'status_extension';
    }
}
