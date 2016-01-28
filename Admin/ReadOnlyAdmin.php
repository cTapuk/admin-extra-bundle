<?php

namespace Vesax\AdminExtraBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class ReadOnlyAdmin
 *
 * @author Artur Vesker
 */
class ReadOnlyAdmin extends Admin
{

    /**
     * @inheritdoc
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'show', 'batch', 'export']);
        parent::configureRoutes($collection);
    }

    /**
     * @inheritdoc
     */
    final protected function configureFormFields(FormMapper $formMapper)
    {
    }

}