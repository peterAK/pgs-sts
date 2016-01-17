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
use PGS\CoreDomainBundle\Model\Avatar\Avatar;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatar;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatarQuery;
use PGS\CoreDomainBundle\Security\Authorizer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.manager.student_avatar")
 */
class StudentAvatarManager extends Authorizer
{

    /**
     * @var ActivePreferenceContainer
     */
    public $activePreference;

    /**
     * @var StudentAvatarQuery
     */
    private $studentAvatarQuery;

    /**
     * @InjectParams({
     *      "activePreference"  = @Inject("pgs.core.container.active_preference"),
     *      "studentAvatarQuery" = @Inject("pgs.core.query.student_avatar")
     * })
     */
    public function __construct(
        ActivePreferenceContainer $activePreference,
        StudentAvatarQuery $studentAvatarQuery
    ) {
        $this->activePreference  = $activePreference;
        $this->studentAvatarQuery = $studentAvatarQuery;
    }

    /**
     * @return StudentAvatarQuery
     */
    public function getBaseQuery()
    {
        return $this->studentAvatarQuery->create();
    }

    /**
     * @return studentAvatar
     */
    public function getDefault()
    {
        if(!$studentAvatar = $this->getCurrentUser()->getUserProfile()->getStudentAvatarId()){
            $studentAvatar = new StudentAvatar();
        }

        return $studentAvatar;
    }

    /**
     * @param mixed $studentAvatar
     *
     * @return StudentAvatar
     */
    public function findOne($studentAvatar)
    {
        if ($studentAvatar instanceof StudentAvatar) {
            $studentAvatar = $studentAvatar->getId();
        }

        return $this->limitList($this->getBaseQuery())->findOneById($studentAvatar);
    }

    /**
     * @return StudentAvatar[]
     */
    public function findAll()
    {
        return $this->getBaseQuery()->find();
    }

    /**
     *
     * @return StudentAvatar[]
     */
    public function findByStudentIdAndSelected($studentId)
    {
        return $this->getBaseQuery()
            ->filterByUserId($studentId)
            ->filterBySelected(true)
            ->find();
    }

    /**
     *
     * @return StudentAvatar[]
     */
    public function findByStudentId($studentId)
    {
        return $this->getBaseQuery()
            ->filterByUserId($studentId)
            ->find();
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return StudentAvatar::getStatuses();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getBaseQuery()->create()->count();
    }

    /**
     * @return int
     */
    public function countActive()
    {
        return $this->getBaseQuery()->filterByStatus('active')->count();
    }

    /**
     * @param StudentAvatar $studentAvatar
     * @param string $direction
     *
     * @return true
     */
    public function StudentAvatar(StudentAvatar $studentAvatar, $direction) {
        switch (strtoupper($direction)) {
            case 'TOP':
                $studentAvatar->moveToTop();
                break;

            case 'UP':
                $studentAvatar->moveUp();
                break;

            case 'DOWN':
                $studentAvatar->moveDown();
                break;

            case 'BOTTOM':
                $studentAvatar->moveToBottom();
                break;
        }

        return true;
    }

    /**
     * @return bool|StudentAvatar
     */
    public function canAdd()
    {
        if ($this->isAdmin()) {
            return new StudentAvatar();
        }

        return false;
    }

    /**
     * @param StudentAvatarQuery $query
     *
     * @return bool|StudentAvatarQuery
     */
    public function limitList($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }
        if ($this->isPrincipal()) {
            return $query;
//                ->useUserQuery()
//                    ->useOrganizationQuery()
//                        ->useSchoolQuery()
//                            ->filterByOrganization($this->getCurrentUser()->getUserProfile()->getOrganizationId())
//                        ->endUse()
//                    ->endUse()
//                ->endUse();
        }
        if ($this->isTeacher()) {
            return $query;
        }
        if ($this->isStudent()) {
            return $query
                ->filterByUserId($this->getCurrentUser()->getId());
        }
    }

    public function limitRole(StudentAvatar $studentAvatar)
    {
        if ($this->isAdmin()) return true;

        return false;
    }

    /**
     * @param mixed $studentAvatar
     *
     * @return bool|StudentAvatar
     */
    public function canEdit($studentAvatar)
    {
        $studentAvatar = $this->findOne($studentAvatar);

        if ($this->limitRole($studentAvatar)) {
            return false;
        }

        return $studentAvatar;
    }

    /**
     * @param mixed $studentAvatar
     *
     * @return bool|StudentAvatar
     */
    public function canDelete($studentAvatar)
    {
        $studentAvatar = $this->findOne($studentAvatar);

        if ($this->limitRole($studentAvatar)) {
            return false;
        }

        return $studentAvatar;
    }

    /**
     * @param mixed $studentAvatar
     *
     * @return bool|StudentAvatar
     */
    public function canView($studentAvatar)
    {
        $studentAvatar = $this->findOne($studentAvatar);

        if ($this->limitRole($studentAvatar)) {
            return false;
        }

        return $studentAvatar;
    }


}
