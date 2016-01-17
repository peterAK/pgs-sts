<?php

namespace PGS\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'password')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('username')
            ->add('userprofile.first_name', null, [ 'label'=>'Name' ])
            ->add('email')
            ->add('enabled')
            ->add('locked')
        ;
    }
}
