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
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use PGS\CoreDomainBundle\Model\Medical\Medical;
use PGS\CoreDomainBundle\Model\Medical\MedicalQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.medical")
 */
class MedicalManager extends Authorizer
{
    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var MedicalQuery
     */
    private $medicalQuery;


    /**
     * @InjectParams({
     *      "medicalQuery" = @Inject("pgs.core.query.medical"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        MedicalQuery $medicalQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ){
        $this->activePreference = $activePreference;
        $this->medicalQuery     = $medicalQuery;
        $this->securityContext  = $securityContext;
    }

    /**
     * @return MedicalQuery
     */
    public function getBaseQuery()
    {
        return $this->medicalQuery->create();
    }

    /**
     * @return Medical[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderBySortableRank()->find();
    }

    /**
     * @param $medical
     * @return mixed
     */
    public function findOne($medical)
    {
        if ($medical instanceof Medical) {
            $medical = $medical->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($medical);
    }

    /**
     * @param MedicalQuery $query
     * @return bool|MedicalQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(Medical $medical)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|Medical
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new Medical;
        }
    }

    /**
     * @param mixed $medical
     * @return bool|Medical
     */
    public function canEdit($medical)
    {
        $medical = $this->findOne($medical);

         if(!$this->limitRole($medical)){
             return false;
         }

        return $medical;
    }

    /**
     * @param mixed $medical
     * @return bool|Medical
     */
    public function canView($medical)
    {
        $medical = $this->findOne($medical);

        if(!$this->limitRole($medical)){
            return false;
        }

        return $medical;
    }

    /**
     * @param mixed $medical
     * @return bool|Medical
     */
    public function canDelete($medical)
    {
        $medical = $this->findOne($medical);

        if(!$this->limitRole($medical)){
            return false;
        }

        return $medical;
    }


}
