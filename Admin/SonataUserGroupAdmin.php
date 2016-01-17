<?php

namespace Vesax\AdminExtraBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Admin\Entity\GroupAdmin;

/**
 * Class SonataUserGroupAdmin
 *
 * @author Artur Vesker
 */
class SonataUserGroupAdmin extends GroupAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('roles', 'vesax_admin_extra_roles')
        ;
    }


}