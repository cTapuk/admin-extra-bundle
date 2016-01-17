<?php

namespace Vesax\AdminExtraBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Vesax\AdminExtraBundle\DependencyInjection\Compiler\AnnotationCompilerPass;
use Vesax\AdminExtraBundle\DependencyInjection\Compiler\RoleProvidersCompilerPass;

/**
 * Class VesaxAdminExtraBundle.
 *
 * @author Artur Vesker
 */
class VesaxAdminExtraBundle extends Bundle
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * VesaxAdminExtraBundle constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RoleProvidersCompilerPass());
    }
}
