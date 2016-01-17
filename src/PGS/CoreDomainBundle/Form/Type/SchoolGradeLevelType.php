<?php

namespace PGS\CoreDomainBundle\Form\Type;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use PGS\CoreDomainBundle\Container\ActivePreferenceContainer;
use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PGS\CoreDomainBundle\Model\School\SchoolQuery;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevelQuery;
use PGS\CoreDomainBundle\Model\SchoolTest\SchoolTestI18n;
use PGS\CoreDomainBundle\Manager\SchoolManager;
use PGS\CoreDomainBundle\Repository\SchoolRepository;
use PGS\CoreDomainBundle\Repository\GradeLevelRepository;
use PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Service("pgs.core.form.type.school_grade_level" )
 * @Tag("form.type", attributes = {"alias" = "school_grade_level"})
 */
class SchoolGradeLevelType extends BaseAbstractType
{
    protected $options = array(
        'data_class'         => 'PGS\CoreDomainBundle\Model\SchoolGradeLevel\SchoolGradeLevel',
        'name'               => 'school_grade_level',
        'translation_domain' => 'forms'
    );

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var SchoolQuery
     */
    private $schoolQuery;

    /**
     * @var GradeLevelQuery
     */
    private $gradeLevelQuery;

    /**
     * @var ActivePreferenceContainer
     */
    protected $activePreference;

    /**
     * @InjectParams({
     *      "securityContext" = @Inject("security.context"),
     *      "activePreference" = @Inject("pgs.core.container.active_preference"),
     *      "schoolQuery" = @Inject("pgs.core.query.school"),
     *      "gradeLevelQuery" = @Inject("pgs.core.query.grade_level"),
     * })
     */
    public function __construct(
        SecurityContext $securityContext,
        ActivePreferenceContainer $activePreference,
        SchoolQuery $schoolQuery,
        GradeLevelQuery $gradeLevelQuery
    ){
        $this->securityContext  = $securityContext;
        $this->activePreference = $activePreference;
        $this->schoolQuery      = $schoolQuery;
        $this->gradeLevelQuery  = $gradeLevelQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $schoolActive = $this->activePreference->getSchoolPreference();
//        $builder->add(
//            'school',
//            'text',
//            [
//                'label'       => 'form.school',
////                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
////                'empty_value' => 'form.school.select',
//                'data'       => $schoolActive->getName(),
//                'constraints' => [ new NotBlank() ],
//                'required'    => false
//            ]
//        );
        $builder->add(
            'school',
            'model',
            [
                'label'       => 'form.school',
                'class'       => 'PGS\CoreDomainBundle\Model\School\School',
                'empty_value' => 'form.school.select',
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );

        $builder->add(
            'gradeLevel',
            'model',
            [
                'label'       => 'form.grade.level',
                'class'       => 'PGS\CoreDomainBundle\Model\GradeLevel\GradeLevel',
                'empty_value' => 'form.grade.level.select',
                'query'       => $this->gradeLevelQuery,
                'constraints' => [ new NotBlank() ],
                'required'    => false
            ]
        );
    }

    /**
     * @return object
     */
    protected function getSchoolChoices()
    {
        return $this->getSchoolManager()->getSchoolChoices($this->securityContext, $this->activePreference);
    }

    /**
     * @return SchoolManager
     */
    protected function getSchoolManager()
    {
//        return new SchoolManager($this->getSchoolRepository());
    }

    /**
     * @return SchoolRepository
     */
    protected function getSchoolRepository()
    {
        return new SchoolRepository();
    }

    protected function getGradeLevelRepository()
    {
        return new GradeLevelRepository();
    }
}
