<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Utility;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Service("pgs.core.utility.user.roles")
 */
class RoleHelper {
    /**
     * @Inject("security.role_hierarchy")
     */
    public $rolesHierarchy;

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = array();

        array_walk_recursive($this->rolesHierarchy, function($val) use (&$roles) {
                $roles[] = $val;
            });

        return array_unique($roles);
    }

    /**
     * @return array
     */
    public function getRoleChoices()
    {
        $arrayRoles = $this->getRoles();

        return $this->convertRolesToChoicesArray($arrayRoles);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function convertRolesToChoicesArray(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) === 'ROLE') {
                $result[$key] = $this->convertRoleToLabel($key);
            }
        }
        return array_unique($result);
    }

    /**
     * @param $role
     *
     * @return string
     */
    private function convertRoleToLabel($role)
    {
        $roleDisplay = str_replace('ROLE_', '', $role);
        $roleDisplay = str_replace('_', ' ', $roleDisplay);
        return ucwords(strtolower($roleDisplay));
    }
} 
