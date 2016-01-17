<?php

namespace Vesax\AdminExtraBundle\RoleProvider;

/**
 * Interface RoleProviderInterface.
 *
 * @author Artur Vesker
 */
interface RoleProviderInterface
{
    /**
     * @return string[]
     */
    public function getRoles();
}
