<?php

/**
 * This file is part of the PGS/CoreDomainBundle package.
 *
 * (c) 2014 Protouch Global Solutions, <info@protouchcomputer.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PGS\CoreDomainBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CountryType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'PGS\CoreDomainBundle\Model\Country',
        'name'       => 'country',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code');
        $builder->add('name');
    }
}
