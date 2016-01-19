<?php

namespace Vesax\AdminExtraBundle\Form\Type;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagsType.
 *
 * @author Artur Vesker
 */
class TagsType extends AbstractType
{

    const DEFAULT_CONTEXT_NAME = 'default';

    /**
     * @var string
     */
    protected $contextClass;

    public function __construct($contextClass)
    {
        $this->contextClass = $contextClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $context = $options['context'];

        /**
         * @var \Sonata\DoctrineORMAdminBundle\Admin\FieldDescription
         */
        $fieldDescription = $options['sonata_field_description'];
        $createCallback = $options['create_callback'];

        $class = $fieldDescription->getTargetEntity();
        $manager = $fieldDescription->getAdmin()->getModelManager();

        $builder->addModelTransformer(new CallbackTransformer(
            function ($value) {

                if (!$value) {
                    $value = [];
                }

                if ($value instanceof \Iterator || $value instanceof \IteratorAggregate) {
                    $value = iterator_to_array($value);
                }

                return implode(', ', $value);
            },

            function ($value) use ($manager, $class, $createCallback, $context) {
                if (!$value) {
                    $value = '';
                }

                $tags = array_filter(array_map('trim', explode(',', $value)));

                $instances = [];

                foreach ($tags as $tag) {
                    $instance = $manager->findOneBy($class, ['name' => $tag]);

                    if (!$instance) {
                        $contextInstance = $manager->findOneBy($this->contextClass, ['name' => $context]);

                        if (!$contextInstance) {
                            throw new EntityNotFoundException('Context "'.$context.'" not found');
                        }

                        $instance = $createCallback($tag, $contextInstance);
                        $manager->create($instance);
                    }

                    $instances[] = $instance;
                }

                return $instances;
            }
        ));
    }

    /**
     * Build View.
     *
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     *
     * @return TagsType
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tags'] = $options['tags'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['sonata_field_description', 'context'])
            ->setAllowedTypes('sonata_field_description', 'Sonata\DoctrineORMAdminBundle\Admin\FieldDescription')
            ->setDefaults([
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->setDefault('create_callback', function (Options $options) {

                /**
                 * @var \Sonata\DoctrineORMAdminBundle\Admin\FieldDescription
                 */
                $fieldDescription = $options['sonata_field_description'];

                $class = $fieldDescription->getTargetEntity();

                return function ($name, $context) use ($class) {
                    $tag = new $class();

                    $tag->setName($name);
                    $tag->setEnabled(true);

                    if ($context) {
                        $tag->setContext($context);
                    }

                    return $tag;
                };
            })
            ->setDefault('tags', function (Options $options) {

                /**
                 * @var \Sonata\DoctrineORMAdminBundle\Admin\FieldDescription
                 */
                $fieldDescription = $options['sonata_field_description'];

                return $fieldDescription->getAdmin()->getModelManager()->findBy($fieldDescription->getTargetEntity(), ['enabled' => true, 'context' => $options['context']]);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tags';
    }
}
