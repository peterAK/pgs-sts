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
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYear;
use PGS\CoreDomainBundle\Model\AcademicYear\AcademicYearQuery;

/**
 * @Service("pgs.core.repository.academic_year")
 */
class AcademicYearRepository extends AcademicYearQuery
{
    /**
     * @param int $academicYearId
     *
     * @return AcademicYear
     */
    public function setDefault($academicYearId = null)
    {
        $this->create()->update(['Active' => false]);

        if ($academicYearId === null) {
            if (!$activeYear = $this->create()->findOneByActive(true)) {
                $activeYear = $this->create()->orderByYearEnd(\Criteria::DESC)->findOne();
            }
        } else {
            $activeYear = $this->create()->findOneById($academicYearId);
        }

        $activeYear->setActive(true)->save();

        return $activeYear;
    }

    /**
     * @param int $academicYearId
     */
    public function setActive($academicYearId)
    {
        $this->setDefault($academicYearId);
    }
}
