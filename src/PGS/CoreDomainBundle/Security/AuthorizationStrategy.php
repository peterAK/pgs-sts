<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Security;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class AuthorizationStrategy
{
    protected $authorizer;

    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function limit(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterBySelf(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByParent(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByGrandParent(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param \ModelCriteria $query
     *
     * @return \ModelCriteria
     */
    function filterByUser(\ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    function canDelete($object)
    {
        return true;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    function canEdit($object)
    {
        return true;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    function canView($object)
    {
        return true;
    }
}
