<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zantolov\MediaBundle\Form\EventSubscriber\ImagesChooserFieldAdderSubscriber;

class PlayerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $imagesSubscriber = new ImagesChooserFieldAdderSubscriber('image', array('label' => 'Image', 'multiple' => false));
        $builder->addEventSubscriber($imagesSubscriber);

        $builder
            ->add('score')
            ->add('alias')
            ->add('level')
            ->add('calories')
            ->add('steps')
            ->add('active')
            ->add('user')
            ->add('team');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Player'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_player';
    }
}
