<?php

namespace Vesax\AdminExtraBundle\RoleProvider;

/**
 * Class ChainRoleProvider.
 *
 * @author Artur Vesker
 */
class ChainRoleProvider implements RoleProviderInterface
{
    /**
     * @var RoleProviderInterface[]
     */
    protected $providers = [];

    /**
     * @param RoleProviderInterface $roleProvider
     */
    public function addProvider(RoleProviderInterface $roleProvider)
    {
        $this->providers[] = $roleProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = [];

        foreach ($this->providers as $provider) {
            $roles = array_merge($roles, $provider->getRoles());
        }

        return $roles;
    }
}
