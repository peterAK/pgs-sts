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
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;
/**
 * @Service("pgs.core.manager.academic_year")
 */
class AcademicYearManager extends Authorizer
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
     * @var AcademicYearQuery
     */
    private $academicYearQuery;

    /**
     * @InjectParams({
     *      "academicYearQuery" = @Inject("pgs.core.query.academic_year"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "securityContext"  = @Inject("security.context"),
     * })
     */
    public function __construct(
        AcademicYearQuery $academicYearQuery,
        ActivePreferenceContainer $activePreference,
        SecurityContext $securityContext
    ) {
        $this->academicYearQuery      = $academicYearQuery;
        $this->activePreference       = $activePreference;
        $this->securityContext        = $securityContext;

    }

    /**
     * @return academicYearQuery
     */
    public function getBaseQuery()
    {
        return $this->academicYearQuery->create();
    }

    /**
     * @param mixed $academicYear
     *
     * @return AcademicYear
     */
    public function findOne($academicYear)
    {
        if ($academicYear instanceof AcademicYear) {
            $academicYear = $academicYear->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($academicYear);
    }

    /**
     * @return AcademicYear[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->orderByYearStart()->find();
    }

    /**
     * @param mixed $academicYear
     */
    public function setActive($academicYear)
    {
        if ($academicYear instanceof AcademicYear)
        {
            $academicYear = $academicYear->getId();
        }

        $this->getBaseQuery()->setActive($academicYear);
    }

    /**
     * @return \PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearQuery
     */
    public function getChoices()
    {
        return $this->getBaseQuery()->create()->orderByYearStart();
    }

    /**
     * @param AcademicYearQuery $query
     * @return bool|AcademicYearQuery
     */
    public function limitList($query)
    {
        return $query;
    }

    public function limitRole(AcademicYear $academicYear)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @return bool|AcademicYear
     */
    public function canAdd()
    {
        if($this->isAdmin()){
            return new AcademicYear();
        }
        return false;
    }

    /**
     * @param mixed $academicYear
     *
     * @return bool|AcademicYear
     */
    public function canEdit($academicYear)
    {
        $academicYear = $this->findOne($academicYear);

        if($this->limitRole($academicYear)){
            return false;
        }

        return $academicYear;
    }

    /**
     * @param mixed $academicYear
     *
     * @return bool|AcademicYear
     */
    public function canDelete($academicYear)
    {
        $academicYear = $this->findOne($academicYear);

        if($this->limitRole($academicYear)){
            return false;
        }

        return $academicYear;
    }

    /**
     * @param mixed $academicYear
     *
     * @return bool|AcademicYear
     */
    public function canView($academicYear)
    {
        $academicYear = $this->findOne($academicYear);

        if(!$this->limitRole($academicYear)){
            return false;
        }

        return $academicYear;
    }


}
