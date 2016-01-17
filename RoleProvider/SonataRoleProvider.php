<?php

namespace Vesax\AdminExtraBundle\RoleProvider;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Security\Handler\RoleSecurityHandler;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;

/**
 * Class SonataRoleProvider.
 *
 * @author Artur Vesker
 */
class SonataRoleProvider implements RoleProviderInterface
{
    /**
     * @var RoleSecurityHandler
     */
    protected $pool;

    /**
     * @var SecurityHandlerInterface
     */
    protected $securityHandler;

    /**
     * @param Pool                $pool
     * @param RoleSecurityHandler $securityHandler
     */
    public function __construct(Pool $pool, RoleSecurityHandler $securityHandler)
    {
        $this->pool = $pool;
        $this->securityHandler = $securityHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = [];

        foreach ($this->pool->getAdminGroups() as $name => $group) {
            if (!isset($group['items'])) {
                continue;
            }

            foreach ($group['items'] as $item) {
                $admin = $this->pool->getInstance($item['admin']);
                $baseRole = $this->securityHandler->getBaseRole($admin);
                $baseName = $admin->getTranslator()->trans($group['label'], [], $group['label_catalogue']).': '.$admin->getTranslator()->trans($admin->getLabel());
                $actions = [];

                foreach ($admin->getSecurityInformation() as $name => $value) {
                    $parts = explode('.', $name);

                    if (count($parts) < 1) {
                        continue;
                    }

                    $actions[] = strtoupper($parts[count($parts) - 1]);
                }

                foreach ($actions as $action) {
                    $roles[sprintf($baseRole, $action)] = $baseName.': '.$admin->getTranslator()->trans($action);
                }
            }
        }

        return $roles;
    }
}