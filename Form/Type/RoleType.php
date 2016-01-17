<?php

namespace Vesax\AdminExtraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vesax\AdminExtraBundle\RoleProvider\RoleProviderInterface;

/**
 * Class RoleType
 *
 * @author Artur Vesker
 */
class RoleType extends AbstractType
{

    /**
     * @var RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * RoleType constructor.
     *
     * @param RoleProviderInterface $roleProvider
     */
    public function __construct(RoleProviderInterface $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'multiple' => true,
                'choices' => $this->roleProvider->getRoles(),
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'vesax_admin_extra_roles';
    }


}