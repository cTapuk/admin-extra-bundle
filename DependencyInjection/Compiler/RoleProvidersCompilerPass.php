<?php

namespace Vesax\AdminExtraBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RoleProvidersCompilerPass.
 *
 * @author Artur Vesker
 */
class RoleProvidersCompilerPass implements CompilerPassInterface
{
    const TAG_NAME = 'vesax.admin_extra.role_provider';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $chainProvider = $container->findDefinition('vesax.admin_extra.chain_role_provider');

        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $id => $attributes) {
            $chainProvider->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
