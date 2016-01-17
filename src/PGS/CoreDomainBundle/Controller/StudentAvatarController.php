<?php

/**
 * This file is part of the PGS/StudentBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Controller;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use PGS\CoreDomainBundle\Manager\AvatarManager;
use PGS\CoreDomainBundle\Manager\StudentAvatarManager;
use PGS\CoreDomainBundle\Manager\SchoolClassCourseStudentBehaviorManager;
use PGS\CoreDomainBundle\Controller\AbstractCoreBaseController;
use PGS\CoreDomainBundle\Model\StudentAvatar\StudentAvatar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class StudentAvatarController extends AbstractCoreBaseController
{

    /**
     * @var SchoolClassCourseStudentBehaviorManager
     * @Inject("pgs.core.manager.school_class_course_student_behavior")
     */
    protected $schoolClassCourseStudentBehaviorManager;

    /**
     * @var StudentAvatarManager
     * @Inject("pgs.core.manager.student_avatar")
     */
    protected $studentAvatarManager;

    /**
     * @var AvatarManager
     * @Inject("pgs.core.manager.avatar")
     */
    protected $avatarManager;

    /**
 * @Template("PGSCoreDomainBundle:StudentAvatar:myAvatar.html.twig")
 */
    public function myAvatarAction(Request $request)
    {
        $studentId  = $this->getUser()->getId();
        $user       = $this->getActivePreference()->getCurrentUserProfile();
        $studentAvatars=$this->studentAvatarManager->findByStudentIdAndSelected($studentId);
//        if(count($studentAvatars)==null){
//            echo($studentId);die;
//        }
        return [
            'model'          => 'StudentAvatar',
            'studentAvatars' => $studentAvatars,
            'user'           => $user
        ];
    }

    /**
     * @Template("PGSCoreDomainBundle:StudentAvatar:list.html.twig")
     */
    public function listAction(Request $request)
    {
        $studentId      = $this->getUser()->getId();
        $user           = $this->getActivePreference()->getCurrentUserProfile();
        $avatars        = $this->avatarManager->findAll();
        $studentAvatars = $this->studentAvatarManager->findByStudentId($studentId);

        $myBehaviorPoint=$this->schoolClassCourseStudentBehaviorManager->countMyTotalBehaviorPoint();
        $myTotalPoint=$myBehaviorPoint->getTotalPoint();
        if($myTotalPoint==null){
            $myTotalPoint=0;
        }

        foreach($avatars as $avatar){
            if($myTotalPoint>=$avatar->getMinPoint()){
                $check=false;
                foreach($studentAvatars as $studentAvatar){
                    if($avatar->getId()!=$studentAvatar->getAvatarId()){
                        $check=true;
                    }
                    else{
                        $check=false;break;
                    }
                }
                if($check==true){
                    $newAvatar=new StudentAvatar();
                    $newAvatar->setUserId($studentId);
                    $newAvatar->setSelected(0);
                    $newAvatar->setAvatarId($avatar->getId());
                    $newAvatar->save();
                }
                $check=false;
            }
        }

        return [
            'model'          => 'StudentAvatar',
            'avatars'        => $avatars,
            'studentAvatars' => $studentAvatars,
            'user'           => $user
        ];
    }

    /**
     *
     */
    public function selectAction(Request $request)
    {
        $id         = $request->get('id');
        $studentId  = $this->getUser()->getId();
        $studentAvatars=$this->studentAvatarManager->findByStudentId($studentId);
        foreach($studentAvatars as $studentAvatar){
            if($studentAvatar->getSelected()==1){
                $studentAvatar->setSelected(0);
                $studentAvatar->save();
            }
            elseif($studentAvatar->getId()==$id){
//                echo($studentAvatar->getId()." sama dengan".$id);die;
                $studentAvatar->setSelected(1);
                $studentAvatar->save();
            }
        }
        return new RedirectResponse($this->generateUrl('pgs_core_student_avatar_my_avatar'));

    }
}
