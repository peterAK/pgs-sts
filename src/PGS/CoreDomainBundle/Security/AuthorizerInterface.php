<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Created by Peter on 11/30/2014.
 */

namespace PGS\CoreDomainBundle\Security;

interface AuthorizerInterface
{
    /**
     * @param mixed $modelIdentifier
     * @param int $status
     */
    public function limitList($modelIdentifier);

    public function canAdd();

    /** @param mixed $modelIdentifier */
    public function canEdit($modelIdentifier);

    /** @param mixed $modelIdentifier */
    public function canDelete($modelIdentifier);

    /** @param mixed $modelIdentifier */
    public function canView($modelIdentifier);
}
